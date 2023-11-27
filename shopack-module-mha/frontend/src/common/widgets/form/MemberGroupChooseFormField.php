<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\widgets\form;

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupModel;
use shopack\base\frontend\common\widgets\FormBuilder;
use Yii;

class MemberGroupChooseFormField
{
	public static function field(
		$view,
		$model,
		$attribute,
		$allowClear = true,
		$multiSelect = false
	) {
		$formatJs =<<<JS
var formatMemberGroup = function(item)
{
	if (item.loading)
		return 'در حال جستجو...'; //item.text;
	return '<div style="overflow:hidden;">' + item.title + '</div>';
};
var formatMemberGroupSelection = function(item)
{
	if (item.text)
		return item.text;
	return item.title;
}
JS;
		$view->registerJs($formatJs, \yii\web\View::POS_HEAD);

		// script to parse the results into the format expected by Select2
		$resultsJs =<<<JS
function(data, params)
{
	if ((data == null) || (params == null))
		return;

	// params.page = params.page || 1;
	if (params.page == null)
		params.page = 0;
	return {
		results: data.items,
		pagination: {
			more: ((params.page + 1) * 20) < data.total_count
		}
	};
}
JS;

		if (!empty($model->$attribute)) {
			if ($multiSelect) {
				$models = MemberGroupModel::findAll($model->$attribute);
				$vals = [];
				$desc = [];
				foreach ($models as $item) {
					$vals[] = $item->mgpID;
					$desc[] = $item->mgpName;
				}
				$model->$attribute = $vals;
			} else {
				$memberGroupModel = MemberGroupModel::findOne($model->$attribute);
				$vals = $model->$attribute;
				$desc = $memberGroupModel->mgpName;
			}
		} else {
			$vals = $model->$attribute;
			$desc = null;
		}

		return [
			$attribute,
			'type' => FormBuilder::FIELD_WIDGET,
			'widget' => Select2::class,
			'widgetOptions' => [
				'value' => $vals,
				'initValueText' => $desc,
				'pluginOptions' => [
					'allowClear' => $allowClear,
					'minimumInputLength' => 3,
					'ajax' => [
						'url' => Url::to(['/mha/member-group/select2-list']),
						'dataType' => 'json',
						'delay' => 50,
						'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true,
					],
					'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
					'templateResult' => new JsExpression('formatMemberGroup'),
					'templateSelection' => new JsExpression('formatMemberGroupSelection'),
				],
				'options' => [
					'placeholder' => Yii::t('app', '-- Search (*** for all) --'),
					'dir' => 'rtl',
					'multiple' => $multiSelect,
				],
			],
		];
	}

}
