<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;
use yii\db\Expression;

class m260710_175626_mha_create_message_templates_for_kanoon extends Migration
{
    public function safeUp()
    {
        $this->execute("UPDATE tbl_AAA_MessageTemplate SET mstName='خانه موسیقی: ارسال پیام به مدیران کانون', mstParams = 'kanoon,member,message' WHERE mstKey = 'mha:kanoonMessageToDirectors';");
        $this->execute("UPDATE tbl_AAA_MessageTemplate SET mstName='خانه موسیقی: ارسال پیام به اعضای کانون',  mstParams = 'kanoon,member,message' WHERE mstKey = 'mha:kanoonMessageToMembers';");

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
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_WaitForSend',           'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به منتظر ارسال',          "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "به منتظر ارسال به کمیسیون تبدیل شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_WaitForSurvey',         'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به منتظر بررسی',          "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "به منتظر بررسی توسط کمیسیون تبدیل شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_WaitForResurvey',       'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به منتظر بررسی مجدد',     "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "به منتظر بررسی مجدد توسط کمیسیون تبدیل شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_Azmoon',                'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به نیاز به آزمون',        "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "به نیاز به آزمون تبدیل شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_Accepted',              'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به تایید شده',            "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "تایید شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_Accepted_With_RegCode', 'S', 'fa', 'D', '{{', '}}', 'member,kanoon,reg-code', 1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به تایید شده با کد جدید', "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "تایید شد و کد عضویت {{reg-code}} برای شما صادر شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_Rejected',              'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به رد شده',               "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "رد شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_Cancelled',             'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به لغو شده',              "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "لغو شد"],
            [new Expression('UUID()'), 'mha:kanoonMembershipRequest_WaitForDocuments',      'S', 'fa', 'D', '{{', '}}', 'member,kanoon',          1, 'خانه موسیقی: تغییر وضعیت عضویت در کانون به منتظر مدارک',          "عضو محترم {{member}}\n" . "درخواست شما برای عضویت در کانون {{kanoon}}\n" . "به منتظر ارسال مدارک تبدیل شد"],
        ]);
    }

    public function safeDown()
    {
        echo "m260710_175626_mha_create_message_templates_for_kanoon cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    }

    public function down()
    {
        echo "m260710_175626_mha_create_message_templates_for_kanoon cannot be reverted.\n";
        return false;
    }
    */
}
