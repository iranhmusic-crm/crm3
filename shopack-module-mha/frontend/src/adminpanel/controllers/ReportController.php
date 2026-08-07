<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\helpers\Html;
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

        // $dirtyAttributes = $model->getDirtyAttributes();
        if (empty($model->rptOutputFields)) {
            $rptOutputFields = [];
            $outputFields = $model->outputFields();

            foreach ($outputFields as $k => $v) {
                if (is_array($v) && key_exists('checked', $v)) {
                    $rptOutputFields[] = $k;
                }
            }

            $model->rptOutputFields = $rptOutputFields;
        }

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

    public function actionExport($id)
    {
        $model = $this->findModel($id);

        $rows = $model->export();

        if (empty($rows)) {
            return 'empty';
        }

        $content = [];

        //--header
        $labels = [];
        $outputFields = $model->outputFields();
        foreach ($outputFields as $fieldName => $fieldSchema) {
            if (isset($fieldSchema['export']) && ($fieldSchema['export'] === false))
                continue;

            $labels[] = is_array($fieldSchema) ? $fieldSchema['label'] : $fieldSchema;
        }
        // foreach ($data[0] as $k => $v) {
        //   if (isset($outputFields[$k]['label'])) {
        //     $labels[] = $outputFields[$k]['label'];
        //   } else if (isset($outputFields[$k])) {
        //     $labels[] = $outputFields[$k];
        //   } else {
        //     $labels[] = $k;
        //   }
        // }
        $content[] = implode(',', $labels);

        //rows
        foreach ($rows as $row) {
            $line = [];

            foreach ($outputFields as $fieldName => $fieldSchema) {
                if (isset($fieldSchema['export']) && ($fieldSchema['export'] === false))
                    continue;

                if (isset($fieldSchema['export'])) {
                    $line[] = call_user_func($fieldSchema['export'], $row);
                } else if (isset($fieldSchema['value'])) {
                    $line[] = call_user_func($fieldSchema['value'], $row, null, null, null);
                } else {
                    $line[] = ArrayHelper::getValue($row, $fieldName);
                }
            }

            $content[] = implode(',', $line);
        }

        $content = implode("\r\n", $content);

        $fileName = "report_{$id}_" . date('Ymd_His') . '.csv';
        return $this->response->sendContentAsFile($content, $fileName);
    }
}
