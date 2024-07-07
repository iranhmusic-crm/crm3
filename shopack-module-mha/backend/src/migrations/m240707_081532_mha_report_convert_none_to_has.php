<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240707_081532_mha_report_convert_none_to_has extends Migration
{
	public function safeUp()
	{
    $this->execute(<<<SQL
	UPDATE	tbl_MHA_Report
		 SET	rptInputFields = REPLACE(rptInputFields, '_None": "1"', '_Has":"0"')
SQL
    );

    $this->execute(<<<SQL
	UPDATE	tbl_MHA_Report
		 SET	rptInputFields = REPLACE(rptInputFields, '_None":"1"', '_Has":"0"')
SQL
    );

//     $this->execute(<<<SQL
// 	UPDATE	tbl_MHA_Report
// 		 SET	rptInputFields = REGEXP_REPLACE(
// 						rptInputFields,
// 						'"([^_]+)_None":(\s*)"1"',
// 						'"\\1_Has":"0"'
// 					)
// SQL
//     );

	}

	public function safeDown()
	{
		echo "m240707_081532_mha_report_convert_none_to_has cannot be reverted.\n";
		return false;
	}

}
