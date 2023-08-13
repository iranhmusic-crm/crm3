<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230522_093620_mha_create_message_templates
 */
class m230522_093620_mha_create_message_templates extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
    $this->batchInsertIgnore('tbl_AAA_MessageTemplate', [
      'mstKey',
      'mstMedia',
      'mstLanguage',
      'mstTitle',
      'mstBody',
      'mstParamsPrefix',
      'mstParamsSuffix',
			'mstIsSystem',
    ], [
			[ 'mha:kanoonMessageToDirectors', 'S', 'fa', NULL, "عضو محترم هیات مدیره کانون {{kanoon}}\n{{member}}\n{{message}}", '{{', '}}', 1 ],
			[ 'mha:kanoonMessageToMembers', 'S', 'fa', NULL, "عضو محترم کانون {{kanoon}}\n{{member}}\n{{message}}", '{{', '}}', 1 ],
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m230522_093620_mha_create_message_templates cannot be reverted.\n";
		return false;
	}

}
