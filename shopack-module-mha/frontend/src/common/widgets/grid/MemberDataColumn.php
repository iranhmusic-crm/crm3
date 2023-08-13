<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\widgets\grid;

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\widgets\Select2;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

class MemberDataColumn extends \kartik\grid\DataColumn
{
	protected function renderFilterCellContent()
	{
		$formatJs =<<<JS
var formatMember = function(item) {
	if (item.loading)
		return 'در حال جستجو...'; //item.text;
	return '<div style="overflow:hidden;">' + item.name + '</div>';
};
var formatMemberSelection = function(item) {
	if (item.text)
		return item.text;
	return item.name;
}
JS;
			$this->grid->view->registerJs($formatJs, \yii\web\View::POS_HEAD);

			// script to parse the results into the format expected by Select2
			$resultsJs =<<<JS
function(data, params) {
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

		$model = $this->grid->filterModel;
		$attribute = $this->attribute;

		if (empty($model->$attribute))
			$initValueText = null;
		else {
			$memberModel = MemberModel::findOne($model->$attribute);
			$initValueText = $memberModel->displayName();
		}

		$widgetOptions = [
			'model' => $model,
			'attribute' => $attribute,
			'initValueText' => $initValueText,
			'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 2,
				'ajax' => [
					'url' => Url::to(['/mha/member/select2-list']),
					'dataType' => 'json',
					'delay' => 50,
					'data' => new JsExpression('function(params) { return { q:params.term, page:params.page }; }'),
					'processResults' => new JsExpression($resultsJs),
					'cache' => true,
				],
				'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
				'templateResult' => new JsExpression('formatMember'),
				'templateSelection' => new JsExpression('formatMemberSelection'),
			],
			'options' => [
				'prompt' => '-- همه --',
			],
		];

		return Select2::widget($widgetOptions);
	}

}
