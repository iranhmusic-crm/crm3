<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\common\models\BasketModel;

class BasketController extends BaseController
{
	public function init()
  {
    parent::init();

    $viewPath = dirname(dirname(__FILE__))
      . DIRECTORY_SEPARATOR
      . 'views'
      . DIRECTORY_SEPARATOR
      . $this->id;

    $this->setViewPath($viewPath);
  }

	public function actionIndex()
	{
		return $this->redirect('/aaa/basket');
	}

	public function actionAddItem()
	{
		$model = new BasketModel();
	}

	public function actionUpdateItem()
	{
	}

	public function actionRemoveItem($key)
	{
	}

}
