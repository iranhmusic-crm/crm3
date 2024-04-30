<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240430_110937_mha_add_mbrExpireDateLastAlertAtDayDiff_to_tblmember extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member`
	ADD COLUMN `mbrExpireDateLastAlertAtDayDiff` SMALLINT NULL DEFAULT NULL AFTER `mbrExpireDate`;
SQL
		);

		$this->execute("DROP TRIGGER IF EXISTS trg_tbl_MHA_Member_before_update;");
		$this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_Member_before_update` BEFORE UPDATE ON `tbl_MHA_Member` FOR EACH ROW BEGIN
	IF (IFNULL(NEW.mbrExpireDate, '2000-1-1') <> IFNULL(OLD.mbrExpireDate, '2000-1-1')) THEN
		SET NEW.mbrExpireDateLastAlertAtDayDiff = NULL;
	END IF;
END
SQL
		);

		$this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Member;");
		$this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_Member AFTER UPDATE ON tbl_MHA_Member FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrAcceptedAt) != ISNULL(NEW.mbrAcceptedAt) OR OLD.mbrAcceptedAt != NEW.mbrAcceptedAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrAcceptedAt", IF(ISNULL(OLD.mbrAcceptedAt), NULL, OLD.mbrAcceptedAt))); END IF;
  IF ISNULL(OLD.mbrArtDegree) != ISNULL(NEW.mbrArtDegree) OR OLD.mbrArtDegree != NEW.mbrArtDegree THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrArtDegree", IF(ISNULL(OLD.mbrArtDegree), NULL, OLD.mbrArtDegree))); END IF;
  IF ISNULL(OLD.mbrArtHistory) != ISNULL(NEW.mbrArtHistory) OR OLD.mbrArtHistory != NEW.mbrArtHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrArtHistory", IF(ISNULL(OLD.mbrArtHistory), NULL, OLD.mbrArtHistory))); END IF;
  IF ISNULL(OLD.mbrExpireDate) != ISNULL(NEW.mbrExpireDate) OR OLD.mbrExpireDate != NEW.mbrExpireDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrExpireDate", IF(ISNULL(OLD.mbrExpireDate), NULL, OLD.mbrExpireDate))); END IF;
  IF ISNULL(OLD.mbrExpireDateLastAlertAtDayDiff) != ISNULL(NEW.mbrExpireDateLastAlertAtDayDiff) OR OLD.mbrExpireDateLastAlertAtDayDiff != NEW.mbrExpireDateLastAlertAtDayDiff THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrExpireDateLastAlertAtDayDiff", IF(ISNULL(OLD.mbrExpireDateLastAlertAtDayDiff), NULL, OLD.mbrExpireDateLastAlertAtDayDiff))); END IF;
  IF ISNULL(OLD.mbrHonarCreditCode) != ISNULL(NEW.mbrHonarCreditCode) OR OLD.mbrHonarCreditCode != NEW.mbrHonarCreditCode THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrHonarCreditCode", IF(ISNULL(OLD.mbrHonarCreditCode), NULL, OLD.mbrHonarCreditCode))); END IF;
  IF ISNULL(OLD.mbrInstrumentID) != ISNULL(NEW.mbrInstrumentID) OR OLD.mbrInstrumentID != NEW.mbrInstrumentID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrInstrumentID", IF(ISNULL(OLD.mbrInstrumentID), NULL, OLD.mbrInstrumentID))); END IF;
  IF ISNULL(OLD.mbrJob) != ISNULL(NEW.mbrJob) OR OLD.mbrJob != NEW.mbrJob THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrJob", IF(ISNULL(OLD.mbrJob), NULL, OLD.mbrJob))); END IF;
  IF ISNULL(OLD.mbrMusicEducationHistory) != ISNULL(NEW.mbrMusicEducationHistory) OR OLD.mbrMusicEducationHistory != NEW.mbrMusicEducationHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrMusicEducationHistory", IF(ISNULL(OLD.mbrMusicEducationHistory), NULL, OLD.mbrMusicEducationHistory))); END IF;
  IF ISNULL(OLD.mbrMusicExperiences) != ISNULL(NEW.mbrMusicExperiences) OR OLD.mbrMusicExperiences != NEW.mbrMusicExperiences THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrMusicExperiences", IF(ISNULL(OLD.mbrMusicExperiences), NULL, OLD.mbrMusicExperiences))); END IF;
  IF ISNULL(OLD.mbrMusicExperienceStartAt) != ISNULL(NEW.mbrMusicExperienceStartAt) OR OLD.mbrMusicExperienceStartAt != NEW.mbrMusicExperienceStartAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrMusicExperienceStartAt", IF(ISNULL(OLD.mbrMusicExperienceStartAt), NULL, OLD.mbrMusicExperienceStartAt))); END IF;
  IF ISNULL(OLD.mbrOwnOrgName) != ISNULL(NEW.mbrOwnOrgName) OR OLD.mbrOwnOrgName != NEW.mbrOwnOrgName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrOwnOrgName", IF(ISNULL(OLD.mbrOwnOrgName), NULL, OLD.mbrOwnOrgName))); END IF;
  IF ISNULL(OLD.mbrRegisterCode) != ISNULL(NEW.mbrRegisterCode) OR OLD.mbrRegisterCode != NEW.mbrRegisterCode THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrRegisterCode", IF(ISNULL(OLD.mbrRegisterCode), NULL, OLD.mbrRegisterCode))); END IF;
  IF ISNULL(OLD.mbrResearchID) != ISNULL(NEW.mbrResearchID) OR OLD.mbrResearchID != NEW.mbrResearchID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrResearchID", IF(ISNULL(OLD.mbrResearchID), NULL, OLD.mbrResearchID))); END IF;
  IF ISNULL(OLD.mbrSingID) != ISNULL(NEW.mbrSingID) OR OLD.mbrSingID != NEW.mbrSingID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrSingID", IF(ISNULL(OLD.mbrSingID), NULL, OLD.mbrSingID))); END IF;
  IF ISNULL(OLD.mbrStatus) != ISNULL(NEW.mbrStatus) OR OLD.mbrStatus != NEW.mbrStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrStatus", IF(ISNULL(OLD.mbrStatus), NULL, OLD.mbrStatus))); END IF;
  IF ISNULL(OLD.mbrUUID) != ISNULL(NEW.mbrUUID) OR OLD.mbrUUID != NEW.mbrUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrUUID", IF(ISNULL(OLD.mbrUUID), NULL, OLD.mbrUUID))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Member"
          , atlInfo   = JSON_OBJECT("mbrUserID", OLD.mbrUserID, "old", Changes);
  END IF;
END
SQL
		);
	}

	public function safeDown()
	{
		echo "m240430_110937_mha_add_mbrExpireDateLastAlertAtDayDiff_to_tblmember cannot be reverted.\n";
		return false;
	}

}
