<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\widgets\grid;

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\widgets\Select2;
use iranhmusic\shopack\mha\frontend\common\models\SupplementaryInsurerModel;

class SupplementaryInsurerDataColumn extends \kartik\grid\DataColumn
{
	protected function renderFilterCellContent()
	{
		$model = $this->grid->filterModel;
		$attribute = $this->attribute;

		if (empty($model->$attribute))
			$initValueText = null;
		else {
			$SupplementaryInsurerModel = SupplementaryInsurerModel::findOne($model->$attribute);
			$initValueText = $SupplementaryInsurerModel->sinsName;
		}

		$widgetOptions = [
			'model' => $model,
			'attribute' => $attribute,
			'initValueText' => $initValueText,
			'data' => ArrayHelper::map(SupplementaryInsurerModel::find()->asArray()->all(), 'sinsID', 'sinsName'),
			'pluginOptions' => [
				'allowClear' => true,
			],
			'options' => [
				'prompt' => '-- همه --',
			],
		];

		return Select2::widget($widgetOptions);
	}

}
