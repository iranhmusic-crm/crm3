<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use yii\web\UnprocessableEntityHttpException;

class MemberDocumentModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberDocumentModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Member_Document}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrdocCreatedAt',
				'createdByAttribute' => 'mbrdocCreatedBy',
				'updatedAtAttribute' => 'mbrdocUpdatedAt',
				'updatedByAttribute' => 'mbrdocUpdatedBy',
			],
		];
	}

  public static function find()
  {
    $query = parent::find();

    $query
      ->select(self::selectableColumns())
      ->with('file')
    ;

    return $query;
  }

	public function save($runValidation = true, $attributeNames = null)
	{
		if (empty($_FILES) == false) {
			$uploadResult = Yii::$app->fileManager->saveUploadedFiles($this->mbrdocMemberID, 'document');

			if (empty($uploadResult))
				return false;

			foreach ($uploadResult as $k => $v) {
				$this->$k = $v['fileID'];
			}
		}

		// if ($this->validate() == false)
		// throw new UnprocessableEntityHttpException(implode("\n", $this->getFirstErrors()));

		$result = parent::save($runValidation, $attributeNames);

		return $result;
	}

}
