<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuReportType;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;

/*
rptID
rptUUID
rptName
rptType
rptInputFields
rptOutputFields
rptStatus
rptCreatedAt
rptCreatedBy
rptUpdatedAt
rptUpdatedBy
rptRemovedAt
rptRemovedBy
*/
trait ReportModelTrait
{
	public static $primaryKey = ['rptID'];

	public function primaryKeyValue() {
		return $this->rptID;
	}

	public function columnsInfo()
	{
		return [
			'rptID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'rptUUID' => ModelColumnHelper::UUID(),
			'rptName' => [
				enuColumnInfo::type       => ['string', 'max' => 512],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'rptType' => [
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null, //enuReportTye
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'rptInputFields' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false, //true,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'rptOutputFields' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false, //true,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'rptStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuReportStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'rptCreatedAt' => ModelColumnHelper::CreatedAt(),
      'rptCreatedBy' => ModelColumnHelper::CreatedBy(),
      'rptUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'rptUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'rptRemovedAt' => ModelColumnHelper::RemovedAt(),
			'rptRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'rptCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'rptUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'rptRemovedBy']);
	}

}
