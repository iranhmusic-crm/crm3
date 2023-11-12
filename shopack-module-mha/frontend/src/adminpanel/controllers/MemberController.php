<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\frontend\common\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

class MemberController extends BaseCrudController
{
	public $modelClass = MemberModel::class;
	public $searchModelClass = MemberSearchModel::class;

  //override
  public function actionView($id)
  {
    try {
      $model = $this->findModel($id);
    } catch (\Throwable $exp) {
      return $this->redirect(['/aaa/user/view', 'id' => $id]);
    }

    $params = [
      'model' => $model,
		];

		if (Yii::$app->request->isAjax)
			return $this->renderJson($this->renderAjax('_view', $params));

    return $this->render('view', $params);
  }

  public function actionPrintCardFront($id)
  {
    // try {
      $model = $this->findModel($id);

      if (empty($model->user->usrImageFileID))
        throw new UnprocessableEntityHttpException('تصویر عضو تعیین نشده است.');

      if (empty($model->user->imageFile->fullFileUrl))
        throw new UnprocessableEntityHttpException('تصویر عضو هنوز آپلود نشده است.');

      $searchModel = new MemberKanoonModel();
      $mbrkanoons = $searchModel->find()
        ->andWhere(['mbrknnMemberID' => $model->mbrUserID])
        ->andWhere(['mbrknnStatus' => enuMemberKanoonStatus::Accepted])
        ->orderBy('mbrknnIsMaster DESC')
        ->all();

      if (empty($mbrkanoons))
        throw new UnprocessableEntityHttpException('این عضو دارای هیچ کانون تایید شده‌ای نیست.');

    // } catch (\Throwable $exp) {
    //   return $this->redirect(['/aaa/user/view', 'id' => $id]);
    // }

    Yii::$app->controller->layout = "/print";

    return $this->render('printCardFront', [
      'model' => $model,
      'mbrkanoons' => $mbrkanoons,
		]);
  }

  public function actionPrintCardBack($id)
  {
    $model = $this->findModel($id);

    $searchModel = new MemberKanoonModel();
    $mbrkanoons = $searchModel->find()
      ->andWhere(['mbrknnMemberID' => $model->mbrUserID])
      ->andWhere(['mbrknnStatus' => enuMemberKanoonStatus::Accepted])
      ->orderBy('mbrknnIsMaster DESC')
      ->all();

    if (empty($mbrkanoons))
      throw new UnprocessableEntityHttpException('این عضو دارای هیچ کانون تایید شده‌ای نیست.');

    Yii::$app->controller->layout = "/print";

    return $this->render('printCardBack', [
      'model' => $model,
      'mbrkanoons' => $mbrkanoons,
		]);
  }

  public function actionPrintArtFundLetter($id)
  {
    try {
      $model = $this->findModel($id);
    } catch (\Throwable $exp) {
      return $this->redirect(['/aaa/user/view', 'id' => $id]);
    }

    Yii::$app->controller->layout = "/print";

    return $this->render('printArtFundLetter', [
      'model' => $model,
		]);
  }

	public function actionSelect2List(
    $q=null,
    // $id=null,
    $page=0,
    $perPage=20
  ) {
    Yii::$app->response->format = Response::FORMAT_JSON;

    $out['total_count'] = 0;
		$out['items'] = [['id' => '', 'name' => '']];

		if (empty($q))
			return $this->renderJson($out);

    //count
    $query = MemberModel::find()
      ->addUrlParameter('q', $q);

    $out['total_count'] = $count = $query->count();
    if ($count == 0)
      return $this->renderJson($out);

    //items
    $query->limit($perPage);
    $query->offset($page * $perPage);
    $models = $query->all();

		$list = [];
    if (empty($models) == false) {
			foreach ($models as $model) {
        $list[] = [
          'id' => $model->mbrUserID,

          'firstname' => $model->user->usrFirstName,
          'lastname'  => $model->user->usrLastName,
          'email'     => $model->user->usrEmail,

          'name' => $model->displayName(),
        ];
			}
    }

    $out['items'] = $list;

    return $this->renderJson($out);
  }

}
