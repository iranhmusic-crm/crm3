<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use shopack\aaa\backend\models\MessageModel;
use shopack\aaa\common\enums\enuGender;

class MemberKanoonModel extends MhaActiveRecord
{
    use \iranhmusic\shopack\mha\common\models\MemberKanoonModelTrait;

    public static function tableName()
    {
        return '{{%MHA_Member_Kanoon}}';
    }

    public $mbrRegisterCode = null;

    public function behaviors()
    {
        return [
            [
                'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
                'createdAtAttribute' => 'mbrknnCreatedAt',
                'createdByAttribute' => 'mbrknnCreatedBy',
                'updatedAtAttribute' => 'mbrknnUpdatedAt',
                'updatedByAttribute' => 'mbrknnUpdatedBy',
            ],
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        //check status changed to Accepted
        $statusChanged = false;
        $accepted = false;

        $values = $this->getDirtyAttributes(['mbrknnStatus']);
        if (empty($values) == false) {
            $oldStatus = $this->oldAttributes['mbrknnStatus'] ?? null;
            $statusChanged = ($oldStatus != $this->mbrknnStatus);
            $accepted = ($statusChanged && ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted));
        }

        // if (empty($this->member->mbrRegisterCode)) {
        // 	$values = $this->getDirtyAttributes(['mbrknnStatus']);
        // 	if (empty($values) == false) {
        // 		if ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted) {
        // 			$oldStatus = $this->oldAttributes['mbrknnStatus'];
        // 			if ($oldStatus !== enuMemberKanoonStatus::Accepted) {
        // 				$accepted = true;
        // 				// throw new UnprocessableEntityHttpException('To confirm the membership, just use the Accept command');
        // 			}
        // 		}
        // 	}
        // }

        if ($accepted) {
            if (empty($this->mbrknnAcceptedAt))
                $this->mbrknnAcceptedAt = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d');
            // throw new UnprocessableEntityHttpException('تاریخ تایید عضویت تعیین نشده است.');

            $transaction = Yii::$app->db->beginTransaction();
        }

        try {
            //moved to trigger
            // $mbrknnHistory = $this->mbrknnHistory;
            // if (empty($mbrknnHistory))
            // 	$mbrknnHistory = [];
            // $mbrknnHistory[] = [
            // 	'at' => new \yii\web\JsExpression('UNIX_TIMESTAMP(NOW())'),
            // 	'status' => $this->mbrknnStatus,
            // 	'comment' => $this->mbrknnComment,
            // ];
            // $this->mbrknnHistory = $mbrknnHistory;

            // save
            if (parent::save($runValidation, $attributeNames) == false)
                throw new UnprocessableEntityHttpException(implode("\n", $this->getFirstErrors()));

            // create register code
            $isNewRegCode = false;
            if ($accepted) {
                if (
                    MemberModel::AssignRegistrationCode(
                        $this->mbrknnMemberID,
                        $this->mbrRegisterCode,
                        $this->mbrknnAcceptedAt
                    )
                    && empty($this->mbrRegisterCode)
                ) {
                    $isNewRegCode = true;

                    //fetch saved mbrRegisterCode
                    $qry = <<<SQL
  SELECT mbrRegisterCode
    FROM tbl_MHA_Member
   WHERE mbrUserID = {$this->mbrknnMemberID}
SQL;
                    $row = Yii::$app->db->createCommand($qry)->queryOne();
                    if (empty($row) == false)
                        $this->mbrRegisterCode = $row['mbrRegisterCode'];
                }
            }

            // send message
            if ($statusChanged) {
                $memberFullName = [];
                if ((empty($this->member->user->usrGender) == false)
                    && ($this->member->user->usrGender != enuGender::NotSet)
                )
                    $memberFullName[] = enuGender::getAbrLabel($this->member->user->usrGender);
                if (empty($this->member->user->usrFirstName) == false)
                    $memberFullName[] = $this->member->user->usrFirstName;
                if (empty($this->member->user->usrLastName) == false)
                    $memberFullName[] = $this->member->user->usrLastName;
                $memberFullName = implode(' ', $memberFullName);

                $messageParams = [
                    'member' => $memberFullName,
                    'kanoon' => $this->kanoon->knnName,
                ];

                $kanoon_status_to_message_key = [
                    enuMemberKanoonStatus::WaitForSend      => 'mha:kanoonMembershipRequest_WaitForSend',
                    enuMemberKanoonStatus::WaitForSurvey    => 'mha:kanoonMembershipRequest_WaitForSurvey',
                    enuMemberKanoonStatus::WaitForResurvey  => 'mha:kanoonMembershipRequest_WaitForResurvey',
                    enuMemberKanoonStatus::Azmoon           => 'mha:kanoonMembershipRequest_Azmoon',
                    enuMemberKanoonStatus::Accepted         => 'mha:kanoonMembershipRequest_Accepted',
                    enuMemberKanoonStatus::Rejected         => 'mha:kanoonMembershipRequest_Rejected',
                    enuMemberKanoonStatus::Cancelled        => 'mha:kanoonMembershipRequest_Cancelled',
                    enuMemberKanoonStatus::WaitForDocuments => 'mha:kanoonMembershipRequest_WaitForDocuments',
                ];

                if ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted && $isNewRegCode) {
                    $messageTemplateKey = 'mha:kanoonMembershipRequest_Accepted_With_RegCode';
                    $messageParams['reg-code'] = $this->mbrRegisterCode;
                } else
                    $messageTemplateKey = $kanoon_status_to_message_key[$this->mbrknnStatus];

                MessageModel::saveNewMessage(
                    $this->mbrknnMemberID,
                    $messageTemplateKey,
                    $this->member->user->usrMobile,
                    $messageParams,
                    'mha:member-kanoon:save',
                    false
                );
            }

            //---------------------------------
            if (isset($transaction))
                $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            if (isset($transaction))
                $transaction->rollBack();

            throw $e;
        }
    }
}
