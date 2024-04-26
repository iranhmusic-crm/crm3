<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230610_154434_mha_change_R_to_J_for_rejected_constants extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMasterInsDoc`
	CHANGE COLUMN `mbrminsdocStatus` `mbrminsdocStatus` CHAR(1) NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, J:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember' COLLATE 'utf8mb4_unicode_ci' AFTER `mbrminsdocDocDate`;
SQL
		);

		$this->execute(<<<SQL
UPDATE `tbl_MHA_MemberMasterInsDoc`
	SET mbrminsdocStatus = 'J'
	WHERE mbrminsdocStatus = 'R';
SQL
		);

		//---------------------
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMasterInsDocHistory`
	CHANGE COLUMN `mbrminsdochstAction` `mbrminsdochstAction` CHAR(1) NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, J:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember' COLLATE 'utf8mb4_unicode_ci' AFTER `mbrminsdochstMasterInsDocID`;
SQL
		);

		$this->execute(<<<SQL
UPDATE `tbl_MHA_MemberMasterInsDocHistory`
	SET mbrminsdochstAction = 'J'
	WHERE mbrminsdochstAction = 'R';
SQL
		);

		//---------------------
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberSupplementaryInsDoc`
	CHANGE COLUMN `mbrsinsdocStatus` `mbrsinsdocStatus` CHAR(1) NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, J:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember' COLLATE 'utf8mb4_unicode_ci' AFTER `mbrsinsdocDocDate`;

SQL
		);

		$this->execute(<<<SQL
UPDATE `tbl_MHA_MemberSupplementaryInsDoc`
	SET mbrsinsdocStatus = 'J'
	WHERE mbrsinsdocStatus = 'R';
SQL
		);

		//---------------------
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberSupplementaryInsDocHistory`
	CHANGE COLUMN `mbrsinsdochstAction` `mbrsinsdochstAction` CHAR(1) NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, J:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember' COLLATE 'utf8mb4_unicode_ci' AFTER `mbrsinsdochstSupplementaryInsDocID`;

SQL
		);

		$this->execute(<<<SQL
UPDATE `tbl_MHA_MemberSupplementaryInsDocHistory`
	SET mbrsinsdochstAction = 'J'
	WHERE mbrsinsdochstAction = 'R';
SQL
		);

		//---------------------
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Kanoon`
	CHANGE COLUMN `mbrknnStatus` `mbrknnStatus` CHAR(1) NOT NULL DEFAULT 'S' COMMENT 'S:WaitForSend, W:WaitForSurvey, E:WaitForResurvey, Z:Azmoon, A:Accepted, J:Rejected' COLLATE 'utf8mb4_unicode_ci' AFTER `mbrknnMembershipDegree`;

SQL
		);

		$this->execute(<<<SQL
UPDATE `tbl_MHA_Member_Kanoon`
	SET mbrknnStatus = 'J'
	WHERE mbrknnStatus = 'R';
SQL
		);

		//---------------------
	}

	public function safeDown()
	{
		echo "m230610_154434_mha_change_R_to_J_for_rejected_constants cannot be reverted.\n";
		return false;
	}

}
