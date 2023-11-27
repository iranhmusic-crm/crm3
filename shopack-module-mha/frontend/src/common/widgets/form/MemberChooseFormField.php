<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\widgets\form;

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use shopack\base\frontend\common\widgets\FormBuilder;
use Yii;

class MemberChooseFormField
{
	public static function field(
		$view,
		$model,
		$attribute,
		$allowClear = true,
		$multiSelect = false
	) {
		if ($multiSelect)
			$nameField = 'title';
		else
			$nameField = 'name';

		$formatJs =<<<JS
var formatMember = function(item)
{
	if (item.loading)
		return 'در حال جستجو...'; //item.text;
	return '<div style="overflow:hidden;">' + item.{$nameField} + '</div>';
};
var formatMemberSelection = function(item)
{
	if (item.text)
		return item.text;
	return item.{$nameField};
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
				$models = MemberModel::findAll($model->$attribute);
				$vals = [];
				$memberDesc = [];
				foreach ($models as $item) {
					$vals[] = $item->mbrUserID;
					$memberDesc[] = $item->displayName('{fn} {ln}');
				}
				$model->$attribute = $vals;
				// $memberDesc = implode('، ', $memberDesc);
			} else {
				$memberModel = MemberModel::findOne($model->$attribute);
				$vals = $model->$attribute;
				$memberDesc = $memberModel->displayName();
			}
		} else {
			$vals = $model->$attribute;
			$memberDesc = null;
		}

		return [
			$attribute,
			'type' => FormBuilder::FIELD_WIDGET,
			'widget' => Select2::class,
			'widgetOptions' => [
				'value' => $vals,
				'initValueText' => $memberDesc,
				'pluginOptions' => [
					'allowClear' => $allowClear,
					'minimumInputLength' => 3,
					'ajax' => [
						'url' => Url::to(['/mha/member/select2-list']),
						'dataType' => 'json',
						'delay' => 50,
						'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true,
					],
					'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
					'templateResult' => new JsExpression('formatMember'),
					'templateSelection' => new JsExpression('formatMemberSelection'),
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
