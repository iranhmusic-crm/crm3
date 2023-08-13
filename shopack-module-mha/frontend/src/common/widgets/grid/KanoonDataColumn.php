<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\widgets\grid;

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\widgets\Select2;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;

class KanoonDataColumn extends \kartik\grid\DataColumn
{
	protected function renderFilterCellContent()
	{
		$model = $this->grid->filterModel;
		$attribute = $this->attribute;

		if (empty($model->$attribute))
			$initValueText = null;
		else {
			$KanoonModel = KanoonModel::findOne($model->$attribute);
			$initValueText = $KanoonModel->knnName;
		}

		$widgetOptions = [
			'model' => $model,
			'attribute' => $attribute,
			'initValueText' => $initValueText,
			'data' => ArrayHelper::map(KanoonModel::find()->asArray()->all(), 'knnID', 'knnName'),
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
