<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\models;

use Yii;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\HttpHelper;

class KanoonSendMessageForm extends Model
{
  public $kanoonID;
  public $targetType; //B:Board of Directors, M:Members
  public $message;

  public function rules()
  {
    return [
      // ['postback', 'required'],
      [['kanoonID',
				'targetType',
				'message',
			], 'required'],
    ];
  }

  public function attributeLabels()
	{
		return [
			'kanoonID'		=> Yii::t('mha', 'Kanoon'),
			'targetType'	=> Yii::t('mha', 'Target'),
			'message'			=> Yii::t('aaa', 'Message'),
		];
	}

  public function process()
  {
    if ($this->validate() == false)
      throw new UnauthorizedHttpException(implode("\n", $this->getFirstErrors()));

    list ($resultStatus, $resultData) = HttpHelper::callApi('mha/kanoon/send-message',
      HttpHelper::METHOD_POST,
      [],
      [
				'kanoonID'   => $this->kanoonID,
				'targetType' => $this->targetType,
				'message'    => $this->message,
			]
    );

    if ($resultStatus < 200 || $resultStatus >= 300)
      throw new HttpException($resultStatus, Yii::t('aaa', $resultData['message'], $resultData));

    return true; //[$resultStatus, $resultData['result']];
  }

}
