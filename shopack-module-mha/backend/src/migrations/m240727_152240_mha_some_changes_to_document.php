<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240727_152240_mha_some_changes_to_document extends Migration
{
	public function safeUp()
	{
		//tbl_MHA_BasicDefinition
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_BasicDefinition`
	ADD UNIQUE INDEX `bdfType_bdfName` (`bdfType`, `bdfName`);
SQL
		);

    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_BasicDefinition`
	CHANGE COLUMN `bdfType` `bdfType` CHAR(1) NOT NULL COMMENT 'I:Instrument, S:Sing, R:Research, D:Member Document Reject Reason' COLLATE 'utf8mb4_unicode_ci' AFTER `bdfUUID`;
SQL
		);

    $this->execute(<<<SQL
INSERT IGNORE INTO `tbl_MHA_BasicDefinition` (bdfUUID, bdfType, bdfName, bdfI18NData)
     VALUES (UUID(), 'D', 'تصویر اشتباه است',      '{"en": {"bdfName": "Invalid Image"}}')
          , (UUID(), 'D', 'تاریخ صدور اشتباه است', '{"en": {"bdfName": "Invalid Issue Date"}}')
;
SQL
		);

		//tbl_MHA_Document
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Document`
	ADD COLUMN `docExtraParamsSchema` JSON NULL AFTER `docType`;
SQL
		);
    $this->alterColumn('tbl_MHA_Document', 'docExtraParamsSchema', $this->json());

    $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Document;");
    $this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_Document AFTER UPDATE ON tbl_MHA_Document FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.docUUID) != ISNULL(NEW.docUUID) OR OLD.docUUID != NEW.docUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docUUID", IF(ISNULL(OLD.docUUID), NULL, OLD.docUUID))); END IF;
  IF ISNULL(OLD.docName) != ISNULL(NEW.docName) OR OLD.docName != NEW.docName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docName", IF(ISNULL(OLD.docName), NULL, OLD.docName))); END IF;
  IF ISNULL(OLD.docType) != ISNULL(NEW.docType) OR OLD.docType != NEW.docType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docType", IF(ISNULL(OLD.docType), NULL, OLD.docType))); END IF;
  IF ISNULL(OLD.docExtraParamsSchema) != ISNULL(NEW.docExtraParamsSchema) OR OLD.docExtraParamsSchema != NEW.docExtraParamsSchema THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docExtraParamsSchema", IF(ISNULL(OLD.docExtraParamsSchema), NULL, OLD.docExtraParamsSchema))); END IF;
  IF ISNULL(OLD.docStatus) != ISNULL(NEW.docStatus) OR OLD.docStatus != NEW.docStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docStatus", IF(ISNULL(OLD.docStatus), NULL, OLD.docStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.docUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.docUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Document"
          , atlInfo   = JSON_OBJECT("docID", OLD.docID, "old", Changes);
  END IF;
END
SQL
		);

		//tbl_MHA_Member_Document
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Document`
	ADD COLUMN `mbrdocExtraParams` JSON NULL AFTER `mbrdocFileID`,
	ADD COLUMN `mbrdocRejectReasonIDs` JSON NULL AFTER `mbrdocComment`;
SQL
		);
    $this->alterColumn('tbl_MHA_Member_Document', 'mbrdocExtraParams', $this->json());
    $this->alterColumn('tbl_MHA_Member_Document', 'mbrdocRejectReasonIDs', $this->json());

    $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Member_Document;");
    $this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_Member_Document AFTER UPDATE ON tbl_MHA_Member_Document FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrdocUUID) != ISNULL(NEW.mbrdocUUID) OR OLD.mbrdocUUID != NEW.mbrdocUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocUUID", IF(ISNULL(OLD.mbrdocUUID), NULL, OLD.mbrdocUUID))); END IF;
  IF ISNULL(OLD.mbrdocMemberID) != ISNULL(NEW.mbrdocMemberID) OR OLD.mbrdocMemberID != NEW.mbrdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocMemberID", IF(ISNULL(OLD.mbrdocMemberID), NULL, OLD.mbrdocMemberID))); END IF;
  IF ISNULL(OLD.mbrdocDocumentID) != ISNULL(NEW.mbrdocDocumentID) OR OLD.mbrdocDocumentID != NEW.mbrdocDocumentID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocDocumentID", IF(ISNULL(OLD.mbrdocDocumentID), NULL, OLD.mbrdocDocumentID))); END IF;
  IF ISNULL(OLD.mbrdocTitle) != ISNULL(NEW.mbrdocTitle) OR OLD.mbrdocTitle != NEW.mbrdocTitle THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocTitle", IF(ISNULL(OLD.mbrdocTitle), NULL, OLD.mbrdocTitle))); END IF;
  IF ISNULL(OLD.mbrdocFileID) != ISNULL(NEW.mbrdocFileID) OR OLD.mbrdocFileID != NEW.mbrdocFileID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocFileID", IF(ISNULL(OLD.mbrdocFileID), NULL, OLD.mbrdocFileID))); END IF;
  IF ISNULL(OLD.mbrdocExtraParams) != ISNULL(NEW.mbrdocExtraParams) OR OLD.mbrdocExtraParams != NEW.mbrdocExtraParams THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocExtraParams", IF(ISNULL(OLD.mbrdocExtraParams), NULL, OLD.mbrdocExtraParams))); END IF;
  IF ISNULL(OLD.mbrdocComment) != ISNULL(NEW.mbrdocComment) OR OLD.mbrdocComment != NEW.mbrdocComment THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocComment", IF(ISNULL(OLD.mbrdocComment), NULL, OLD.mbrdocComment))); END IF;
  IF ISNULL(OLD.mbrdocRejectReasonIDs) != ISNULL(NEW.mbrdocRejectReasonIDs) OR OLD.mbrdocRejectReasonIDs != NEW.mbrdocRejectReasonIDs THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocRejectReasonIDs", IF(ISNULL(OLD.mbrdocRejectReasonIDs), NULL, OLD.mbrdocRejectReasonIDs))); END IF;
  IF ISNULL(OLD.mbrdocHistory) != ISNULL(NEW.mbrdocHistory) OR OLD.mbrdocHistory != NEW.mbrdocHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocHistory", IF(ISNULL(OLD.mbrdocHistory), NULL, OLD.mbrdocHistory))); END IF;
  IF ISNULL(OLD.mbrdocStatus) != ISNULL(NEW.mbrdocStatus) OR OLD.mbrdocStatus != NEW.mbrdocStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocStatus", IF(ISNULL(OLD.mbrdocStatus), NULL, OLD.mbrdocStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrdocUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrdocUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Member_Document"
          , atlInfo   = JSON_OBJECT("mbrdocID", OLD.mbrdocID, "old", Changes);
  END IF;
END
SQL
		);

	}

	public function safeDown()
	{
		echo "m240727_152240_mha_some_changes_to_document cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{
	}

	public function down()
	{
		echo "m240727_152240_mha_some_changes_to_document cannot be reverted.\n";
		return false;
	}
	*/

}
