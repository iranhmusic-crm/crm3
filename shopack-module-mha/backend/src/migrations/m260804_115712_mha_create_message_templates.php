<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\db\Expression;
use shopack\base\common\db\Migration;

class m260804_115712_mha_create_message_templates extends Migration
{
    public function safeUp()
    {
        $this->batchInsertIgnore('tbl_AAA_MessageTemplate', [
            'mstUUID',
            'mstKey',
            'mstMedia',
            'mstLanguage',
            'mstStatus',
            'mstParamsPrefix',
            'mstParamsSuffix',
            'mstParams',
            'mstIsSystem',
            'mstName',
            'mstBody',
        ], [
            [new Expression('UUID()'), 'mha:memberDocumentStatusChanged', 'S', 'fa', 'D', '{{', '}}', 'member,status', 1, 'خانه موسیقی: تغییر وضعیت مدرک', "عضو محترم {{member}}\n" . "وضعیت مدرک شما به {{status}} تغییر کرد."],
        ]);
    }

    public function safeDown()
    {
        echo "m260804_115712_mha_create_message_templates cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    }

    public function down()
    {
        echo "m260804_115712_mha_create_message_templates cannot be reverted.\n";
        return false;
    }
    */
}
