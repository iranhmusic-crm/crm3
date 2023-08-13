<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use yii\db\Expression;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\HttpHelper;
use shopack\aaa\backend\models\MessageModel;
use iranhmusic\shopack\mha\backend\models\KanoonModel;
use iranhmusic\shopack\mha\backend\models\MemberKanoonModel;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserStatus;

class KanoonSendMessageForm extends Model
{
  public $kanoonID;
  public $targetType; //B:Board of Directors, M:Members
  public $message;

  public function rules()
  {
    return [
      [['kanoonID',
				'targetType',
				'message',
			], 'required'],
    ];
  }

  public function process()
  {
    if ($this->validate() == false)
      throw new UnauthorizedHttpException(implode("\n", $this->getFirstErrors()));

    $targetUsers = [];

    if ($this->targetType == 'B') {
      $messageTemplate = 'mha:kanoonMessageToDirectors';

      $kanoonModel = KanoonModel::find()
        ->andWhere(['knnID' => $this->kanoonID])
        ->joinWith('president.user')
        ->joinWith('vicePresident.user')
        ->joinWith('ozv1.user')
        ->joinWith('ozv2.user')
        ->joinWith('ozv3.user')
        ->joinWith('warden.user')
        ->joinWith('talker.user')
        ->one();

      $kanoonName = $kanoonModel->knnName;

      $fnAddTarget = function($id, $role) use($kanoonModel, &$targetUsers) {
        if ((empty($id) == false)
            && ($kanoonModel->$role->user->usrMobile != null)
            && ($kanoonModel->$role->user->usrMobileApprovedAt != null)
            && ($kanoonModel->$role->user->usrStatus != enuUserStatus::Removed)
        ) {
          $targetUsers[] = [
            'usrID'         => $id,
            'usrMobile'     => $kanoonModel->$role->user->usrMobile,
            'usrGender'     => $kanoonModel->$role->user->usrGender,
            'usrFirstName'  => $kanoonModel->$role->user->usrFirstName,
            'usrLastName'   => $kanoonModel->$role->user->usrLastName,
          ];
        }
      };

      $fnAddTarget($kanoonModel->knnPresidentMemberID, 'president');
      $fnAddTarget($kanoonModel->knnVicePresidentMemberID, 'vicePresident');
      $fnAddTarget($kanoonModel->knnOzv1MemberID, 'ozv1');
      $fnAddTarget($kanoonModel->knnOzv2MemberID, 'ozv2');
      $fnAddTarget($kanoonModel->knnOzv3MemberID, 'ozv3');
      $fnAddTarget($kanoonModel->knnWardenMemberID, 'warden');
      $fnAddTarget($kanoonModel->knnTalkerMemberID, 'talker');

    } else {
      $messageTemplate = 'mha:kanoonMessageToMembers';

      $targetUsers = MemberKanoonModel::find()
        ->innerJoinWith('kanoon')
        ->innerJoinWith('member.user')
        ->select([
          'mbrknnMemberID',
          'knnName',
          'usrID',
          'usrMobile',
          'usrGender',
          'usrFirstName',
          'usrLastName',
        ])
        ->andWhere(['mbrknnKanoonID' => $this->kanoonID])
        ->andWhere(['IS', 'usrMobile', new Expression('NOT NULL')])
        ->andWhere(['IS', 'usrMobileApprovedAt', new Expression('NOT NULL')])
        ->andWhere(['!=', 'usrStatus', enuUserStatus::Removed])
        ->asArray()
        ->all();

      $kanoonName = $targetUsers[0]['knnName'] ?? null;
    }

    if (empty($targetUsers))
      throw new UnauthorizedHttpException('Target list is empty');

    $sentCount = 0;
    foreach ($targetUsers as $user) {
      $memberFullName = [];
      if ((empty($userModel['usrGender']) == false)
          && ($user['usrGender'] != enuGender::NotSet))
        $memberFullName[] = enuGender::getAbrLabel($user['usrGender']);
      if (empty($user['usrFirstName']) == false)
        $memberFullName[] = $user['usrFirstName'];
      if (empty($user['usrLastName']) == false)
        $memberFullName[] = $user['usrLastName'];
      $memberFullName = implode(' ', $memberFullName);

      $messageModel = new MessageModel;
      $messageModel->sendNow = false;

      $messageModel->msgUserID   = $user['usrID'];
      $messageModel->msgTypeKey  = $messageTemplate;
      $messageModel->msgTarget   = $user['usrMobile'];
      $messageModel->msgInfo     = [
        // 'mobile'    => $user['usrMobile'],
        // 'gender'    => $user['usrGender'],
        // 'firstName' => $user['usrFirstName'],
        // 'lastName'  => $user['usrLastName'],
        'member'    => $memberFullName,
        'kanoon'    => $kanoonName,
        'message'   => $this->message,
      ];
      $messageModel->msgIssuer   = 'mha:kanoon:sendMessage';

      if ($messageModel->save())
        ++$sentCount;
    }

    return [
      'targetCount' => count($user),
      'sentCount'   => $sentCount,
    ];
  }

}
