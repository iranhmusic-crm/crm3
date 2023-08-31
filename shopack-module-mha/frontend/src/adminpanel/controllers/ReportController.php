<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\ReportModel;
use iranhmusic\shopack\mha\frontend\common\models\ReportSearchModel;
use iranhmusic\shopack\mha\common\enums\enuReportType;

class ReportController extends BaseCrudController
{
	public $modelClass = ReportModel::class;
	public $searchModelClass = ReportSearchModel::class;

  public function actionView_afterFindModel($model)
  {
    return [
      'view' . $model->rptType,
      '_view_' . $model->rptType
    ];
  }

  public function actionCreate_afterCreateModel(&$model)
  {
    $model->rptType = $_GET['rpttyp'];

    return [
      'create' . $model->rptType,
      '_form_' . $model->rptType
    ];
  }

  public function actionUpdate_afterFindModel(&$model)
  {
    return [
      'update' . $model->rptType,
      '_form_' . $model->rptType
    ];
  }

  public function actionRun($id)
  {
		$model = $this->findModel($id);

		$dataProvider = $model->run();

    $viewParams = [
			'dataProvider' => $dataProvider,
      'model' => $model,
		];

		if (Yii::$app->request->isAjax)
			return $this->renderJson($this->renderAjax('_report_' . $model->rptType, $viewParams));

    return $this->render('report' . $model->rptType, $viewParams);
  }

}
