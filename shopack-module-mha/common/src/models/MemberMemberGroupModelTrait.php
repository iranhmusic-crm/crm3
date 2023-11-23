<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;

/*
'mbrmgpID',
'mbrmgpUUID',
'mbrmgpMemberID',
'mbrmgpMemberGroupID',
'mbrmgpStartAt',
'mbrmgpEndAt',
'mbrmgpCreatedAt',
'mbrmgpCreatedBy',
'mbrmgpUpdatedAt',
'mbrmgpUpdatedBy',
'mbrmgpRemovedAt',
'mbrmgpRemovedBy',
*/
trait MemberMemberGroupModelTrait
{
  public static $primaryKey = ['mbrmgpID'];

	public function primaryKeyValue() {
		return $this->mbrmgpID;
	}

  public function columnsInfo()
  {
    return [
      'mbrmgpID' => [
        enuColumnInfo::type       => 'integer',
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mbrmgpUUID' => ModelColumnHelper::UUID(),
      'mbrmgpMemberID' => [
        enuColumnInfo::type       => 'integer',
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => true,
        enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
      ],
      'mbrmgpMemberGroupID' => [
        enuColumnInfo::type       => 'integer',
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => true,
        enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
      ],
      'mbrmgpStartAt' => [
        enuColumnInfo::type       => 'safe', //datetime
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mbrmgpEndAt' => [
        enuColumnInfo::type       => 'safe', //datetime
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],

      'mbrmgpCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrmgpCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrmgpUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrmgpUpdatedBy' => ModelColumnHelper::UpdatedBy(),
      'mbrmgpRemovedAt' => ModelColumnHelper::RemovedAt(),
      'mbrmgpRemovedBy' => ModelColumnHelper::RemovedBy(),
    ];
  }

  public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrmgpCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrmgpUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrmgpRemovedBy']);
	}

  public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
      $className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrmgpMemberID']);
	}
  public function getMemberGroup() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberGroupModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberGroupModel';

		return $this->hasOne($className, ['mgpID' => 'mbrmgpMemberGroupID']);
	}

}
