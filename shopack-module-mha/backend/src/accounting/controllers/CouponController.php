<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\helpers\ExceptionHelper;
use shopack\base\backend\accounting\controllers\BaseCouponController;
use shopack\base\backend\helpers\PrivHelper;

class CouponController extends BaseCouponController
{
	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\CouponModel::class;
}
