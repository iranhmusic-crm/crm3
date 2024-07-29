<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;
use shopack\base\common\validators\JsonValidator;
use shopack\base\common\helpers\JsonSchema;

/*
'docID',
'docUUID',
'docName',
'docType',
'docExtraParamsSchema',
'docStatus',
'docCreatedAt',
'docCreatedBy',
'docUpdatedAt',
'docUpdatedBy',
'docRemovedAt',
'docRemovedBy',
*/
trait DocumentModelTrait
{
	public static $EXPARAM_id					= 'id';
	public static $EXPARAM_name				= 'name';
	public static $EXPARAM_type				= 'type';
	public static $EXPARAM_mandatory	= 'mandatory';

  public static $primaryKey = ['docID'];

	public function primaryKeyValue() {
		return $this->docID;
	}

	public function columnsInfo()
	{
		return [
			'docID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'docUUID' => ModelColumnHelper::UUID(),
			'docName' => [
				enuColumnInfo::type       => ['string', 'max' => 128],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::like,
			],
			'docType' => [
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null, //enuDocumentType
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'docExtraParamsSchema' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				enuColumnInfo::jsonSchema => [
					'fields' => [
						[
							self::$EXPARAM_id,
							'label' => ['app', 'ID'],
							'type' => jsonSchema::TYPE_number,
							'pk' => true,
							// 'default' => 'auto-increment;start=1;step=1',
						],
						[
							self::$EXPARAM_name,
							'label' => ['app', 'Name'],
							'type' => jsonSchema::TYPE_string,
							'allow-null' => false,
						],
						[
							self::$EXPARAM_type,
							'label' => ['app', 'Type'],
							'type' => jsonSchema::TYPE_select,
							'allow-null' => false,
							'data' => [
								'text' => ['app', 'Text'],
								'date' => ['app', 'Date'],
								'time' => ['app', 'Time'],
								'mha:bdef:I' => ['mha', 'Instrument'],
								'mha:bdef:S' => ['mha', 'Sing'],
								'mha:bdef:R' => ['mha', 'Research'],
								'mha:kanoon' => ['mha', 'Kanoon'],
							],
						],
						[
							self::$EXPARAM_mandatory,
							'label' => ['aaa', 'Mandatory'],
							'type' => jsonSchema::TYPE_boolean,
							'default' => false,
						],
					],
				],
				// enuColumnInfo::jsonSchema => jsonSchema::create()
				// 	->field(self::$EXPARAM_id)
				// 		->type(jsonSchema::TYPE_number)
				// 		->pk()

				// 	->field(self::$EXPARAM_name)
				// 		->type(jsonSchema::TYPE_string)
				// 		->label('Name')

				// 	->field(self::$EXPARAM_type)
				// 		->type(jsonSchema::TYPE_string)
				// 		->label('Type')

				// 	->field(self::$EXPARAM_mandatory)
				// 		->type(jsonSchema::TYPE_boolean)
				// 		->label('Is Mandatory')
				// 		->default(false)

				// 	->done()
			],
			'docStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuDocumentStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'docCreatedAt' => ModelColumnHelper::CreatedAt(),
      'docCreatedBy' => ModelColumnHelper::CreatedBy(),
      'docUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'docUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'docRemovedAt' => ModelColumnHelper::RemovedAt(),
			'docRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'docCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'docUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'docRemovedBy']);
	}

}
