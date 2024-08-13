<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

use function Ramsey\Uuid\v1;

class MemberKanoonModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberKanoonModelTrait;

	public static $resourceName = 'mha/member-kanoon';

	public function extraRules() {
		$formName = strtolower($this->formName());

		return [
			['mbrknnMembershipDegree',
				'required',
				'when' => function ($model) {
					return ($model->mbrknnStatus == enuMemberKanoonStatus::Accepted);
				},
				'whenClient' => "function (attribute, value) {
					return ($('#{$formName}-mbrknnstatus').val() == '" . enuMemberKanoonStatus::Accepted . "');
				}"
			],
		];
	}

	public function attributeLabels()
	{
		return [
			'mbrknnMemberID'         => Yii::t('mha', 'Member'),
			'mbrknnKanoonID'         => Yii::t('mha', 'Kanoon'),
			'mbrknnParams'           => Yii::t('app', 'Parameters'),
			'mbrknnIsMaster'				 => Yii::t('mha', 'Is Master'),
			'mbrknnMembershipDegree' => Yii::t('mha', 'Membership Degree'),
			'mbrknnComment'          => Yii::t('app', 'Comment'),
			'mbrknnHistory'          => Yii::t('app', 'History'),
			'mbrknnAcceptedAt'       => Yii::t('app', 'Accepted At'),
			'mbrknnStatus'           => Yii::t('app', 'Status'),
			'mbrknnCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrknnCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrknnCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrknnUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrknnUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrknnUpdatedBy_User'   => Yii::t('app', 'Updated By'),
		];
	}

	public function attributeHints()
	{
		return [
			'mbrknnAcceptedAt' => 'در صورتی که تاریخ شروع عضویت عضو تعیین نشده باشد، از این تاریخ استفاده خواهد شد.',
		];
	}

	public function load($data, $formName = null) {
		$ret = parent::load($data, $formName);

		//load relations
		try {
      // $this->member->load($data);
      // $this->kanoon->load($data);
		} catch (\Throwable $exp) {}

		return $ret;
	}

	public function isSoftDeleted()
  {
    return false;
  }

	public static function canCreate() {
		return true;
	}

	public function canWaitForSurvey() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::WaitForSend);
	}
	public function canWaitForResurvey() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::Rejected);
	}
	public function canAzmoon() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::WaitForSurvey
						|| $this->mbrknnStatus == enuMemberKanoonStatus::WaitForResurvey);
	}
	public function canAccept() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::Azmoon
						|| $this->mbrknnStatus == enuMemberKanoonStatus::WaitForSurvey
						|| $this->mbrknnStatus == enuMemberKanoonStatus::WaitForResurvey);
	}
	public function canReject() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::Azmoon
						|| $this->mbrknnStatus == enuMemberKanoonStatus::WaitForSurvey
						|| $this->mbrknnStatus == enuMemberKanoonStatus::WaitForResurvey);
	}
	public function canCancel() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted);
	}
	public function canChangeDegree() {
		return ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted);
	}

	/*
	public function doAccept()
	{
    $apiResponse = HttpHelper::callApi('mha/member-kanoon/accept',
      HttpHelper::METHOD_POST,
      [
        'id'                     => $this->mbrknnID,
        'mbrknnMembershipDegree' => $this->mbrknnMembershipDegree,
        'mbrRegisterCode'        => $this->mbrRegisterCode,
      ]
    );

		HttpHelper::throwApiResponseIfFailed($apiResponse, 'mha');

    return true;
	}

	public static function doReject($id)
	{
		if (empty($id))
			throw new NotFoundHttpException('Invalid id');

    $apiResponse = HttpHelper::callApi('mha/member-kanoon/reject',
      HttpHelper::METHOD_POST,
      [
        'id' => $id,
      ]
    );

		HttpHelper::throwApiResponseIfFailed($apiResponse, 'mha');

    return true;
	}
*/

}
