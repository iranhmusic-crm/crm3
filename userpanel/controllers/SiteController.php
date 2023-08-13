<?php

namespace app\controllers;

use Yii;
use app\models\ContactForm;
use shopack\aaa\frontend\common\auth\BaseController;

class SiteController extends BaseController
{
  public function behaviors()
  {
		$behaviors = parent::behaviors();

    $behaviors[BaseController::BEHAVIOR_AUTHENTICATOR]['except'] = [
			'error',
      'captcha',
		];

    // $behaviors['access'] = [
    //   'class' => AccessControl::class,
    //   'rules' => [
    //     [
    //       'allow' => true,
    //       'actions' => [
    //         'error',
    //         'captcha',
    //         'login',
    //       ],
    //     ],
    //     // [
    //     //   // 'actions' => ['logout'],
    //     //   'allow' => true,
    //     //   'roles' => ['@'],
    //     // ],
    //   ],
    // ];

		return $behaviors;
  }

  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex()
  {
    return $this->render('index');
  }

  /**
   * Displays contact page.
   *
   * @return Response|string
   */
  public function actionContact()
  {
    $model = new ContactForm();
    if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
      Yii::$app->session->setFlash('contactFormSubmitted');

      return $this->refresh();
    }
    return $this->render('contact', [
      'model' => $model,
    ]);
  }

  /**
   * Displays about page.
   *
   * @return string
   */
  public function actionAbout()
  {
    return $this->render('about');
  }
}
