<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\widgets\form;

use Yii;
use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\FormBuilder;

class KanoonChooseFormField
{
	public static function field(
		$view,
		$model,
		$attribute,
		$allowClear = true,
		$multiSelect = false,
		$builderOptions = null
	) {
		$formatJs =<<<JS
var formatKanoon = function(item)
{
	if (item.loading)
		return 'در حال جستجو...'; //item.text;
	return '<div style="overflow:hidden;">' + item.title + '</div>';
};
var formatKanoonSelection = function(item)
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

		if (strpos($attribute, '[') !== false) {
			$parts = explode('[', $attribute, 2);

			$attr = $parts[0];
			$key = str_replace("]", "", str_replace("[", "", str_replace("][", ".", $parts[1])));

			$attrValue = ArrayHelper::getValue($model->$attr, $key);
		} else {
			$attrValue = $model->$attribute ?? null;
		}

		if (empty($attrValue)) {
			$vals = null; //$attrValue;
			$desc = null;
		} else {
			if ($multiSelect) {
				$models = KanoonModel::findAll((array)$attrValue);
				$vals = [];
				$desc = [];
				foreach ($models as $item) {
					$vals[] = $item->knnID;
					$desc[] = $item->knnName;
				}

				if (isset($key)) {
					$atv = $model->$attr;
					ArrayHelper::setValue($atv, $key, $vals);
					$model->$attr = $atv;
				} else
					$model->$attribute = $vals;

			} else {
				$KanoonModel = KanoonModel::findOne($attrValue);
				$vals = $attrValue;
				$desc = $KanoonModel->knnName;
			}
		}

		return array_merge_recursive([
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
						'url' => Url::to(['/mha/kanoon/select2-list']),
						'dataType' => 'json',
						'delay' => 50,
						'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true,
					],
					'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
					'templateResult' => new JsExpression('formatKanoon'),
					'templateSelection' => new JsExpression('formatKanoonSelection'),
				],
				'options' => [
					'placeholder' => Yii::t('app', '-- Search (*** for all) --'),
					'dir' => 'rtl',
					'multiple' => $multiSelect,
				],
			],
		], $builderOptions ?? []);
	}

}
