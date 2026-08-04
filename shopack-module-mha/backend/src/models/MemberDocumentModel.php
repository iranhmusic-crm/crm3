<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;
use shopack\aaa\backend\models\MessageModel;
use shopack\aaa\common\enums\enuGender;

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
            // ->select(self::selectableColumns())
            ->with('file');

        return $query;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        // $status = enuMemberDocumentStatus::getLabel($this->mbrdocStatus);
        // return false;

        if (empty($_FILES) == false) {
            $uploadResult = Yii::$app->fileManager->saveUploadedFiles(
                /* userID             */
                $this->mbrdocMemberID,
                /* targetPath         */
                'document',
                /* allowedFileTypes   */
                null,
                /* allowedMimeTypes   */
                ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'],
                /* allowedMinFileSize */
                0,
                /* allowedMaxFileSize */
                2 * 1024 * 1024
            );

            if (empty($uploadResult))
                return false;

            foreach ($uploadResult as $k => $v) {
                $this->$k = $v['fileID'];
            }
        }

        // if ($this->validate() == false)
        // throw new UnprocessableEntityHttpException(implode("\n", $this->getFirstErrors()));

        //-- check status changing
        $statusChanged = false;

        if (false == $this->isNewRecord) {
            $values = $this->getDirtyAttributes(['mbrdocStatus']);
            if (empty($values) == false) {
                $oldStatus = $this->oldAttributes['mbrdocStatus'] ?? null;
                $statusChanged = ($oldStatus != $this->mbrdocStatus);
            }
        }

        //----------------------------
        $result = parent::save($runValidation, $attributeNames);

        //----------------------------
        if ($result && $statusChanged) {
            //-- send message
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

            MessageModel::saveNewMessage(
                $this->mbrdocMemberID,
                'mha:memberDocumentStatusChanged',
                $this->member->user->usrMobile,
                [
                    'member' => $memberFullName,
                    'status' => enuMemberDocumentStatus::getLabel($this->mbrdocStatus),
                ],
                'mha:member-document:save',
                false
            );
        }

        return $result;
    }
}
