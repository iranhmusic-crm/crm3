<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use shopack\interface\accounting\common\enums\enuProductStatus;

class ProductModel extends MhaActiveRecord
{
    use \iranhmusic\shopack\mha\common\accounting\models\ProductModelTrait;

    use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
    public function initSoftDelete()
    {
        $this->softdelete_RemovedStatus  = enuProductStatus::Removed;
        // $this->softdelete_StatusField    = 'prdStatus';
        $this->softdelete_RemovedAtField = 'prdRemovedAt';
        $this->softdelete_RemovedByField = 'prdRemovedBy';
    }

    public static function tableName()
    {
        return '{{%MHA_Accounting_Product}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
                'createdAtAttribute' => 'prdCreatedAt',
                'createdByAttribute' => 'prdCreatedBy',
                'updatedAtAttribute' => 'prdUpdatedAt',
                'updatedByAttribute' => 'prdUpdatedBy',
            ],
        ];
    }
}
