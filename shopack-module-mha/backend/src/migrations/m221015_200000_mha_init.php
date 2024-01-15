<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m221015_200000_mha_init extends Migration
{
	public function up()
	{
    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Member` (
  `mbrUserID` bigint unsigned NOT NULL,
  `mbrRegisterCode` bigint unsigned NOT NULL,
  `mbrAcceptedAt` datetime DEFAULT NULL,
  `mbrMusicExperiences` text COLLATE utf8mb4_unicode_ci,
  `mbrMusicExperienceStartAt` date DEFAULT NULL,
  `mbrArtHistory` text COLLATE utf8mb4_unicode_ci,
  `mbrMusicEducationHistory` text COLLATE utf8mb4_unicode_ci,
  `mbrStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W' COMMENT 'A:Active, D:Disable, W:Wait For Approval, R:Removed',
  `mbrCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrUpdatedAt` datetime DEFAULT NULL,
  `mbrUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrUserID`) USING BTREE,
  KEY `FK_tbl_MHA_Member_tbl_AAA_User_creator` (`mbrCreatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Member_tbl_AAA_User_modifier` (`mbrUpdatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Member_tbl_AAA_User_remover` (`mbrRemovedBy`) USING BTREE,
  CONSTRAINT `FK_tbl_MHA_Member_tbl_AAA_User` FOREIGN KEY (`mbrUserID`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Member_tbl_AAA_User_creator` FOREIGN KEY (`mbrCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Member_tbl_AAA_User_modifier` FOREIGN KEY (`mbrUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Member_tbl_AAA_User_remover` FOREIGN KEY (`mbrRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Document` (
  `docID` int unsigned NOT NULL AUTO_INCREMENT,
  `docName` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `docType` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `docStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `docCreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `docCreatedBy` bigint unsigned DEFAULT NULL,
  `docUpdatedAt` datetime DEFAULT NULL,
  `docUpdatedBy` bigint unsigned DEFAULT NULL,
  `docRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `docRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`docID`),
  KEY `docCreatedAt` (`docCreatedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Kanoon` (
  `knnID` int unsigned NOT NULL AUTO_INCREMENT,
  `knnName` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `knnPresidentMemberID` bigint unsigned DEFAULT NULL,
  `knnVicePresidentMemberID` bigint unsigned DEFAULT NULL,
  `knnOzv1MemberID` bigint unsigned DEFAULT NULL,
  `knnOzv2MemberID` bigint unsigned DEFAULT NULL,
  `knnOzv3MemberID` bigint unsigned DEFAULT NULL,
  `knnWardenMemberID` bigint unsigned DEFAULT NULL,
  `knnTalkerMemberID` bigint unsigned DEFAULT NULL,
  `knnStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `knnCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `knnCreatedBy` bigint unsigned DEFAULT NULL,
  `knnUpdatedAt` datetime DEFAULT NULL,
  `knnUpdatedBy` bigint unsigned DEFAULT NULL,
  `knnRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `knnRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`knnID`) USING BTREE,
  KEY `FK_tbl_MHA_Kanoon_tbl_AAA_User_creator` (`knnCreatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Kanoon_tbl_AAA_User_modifier` (`knnUpdatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Kanoon_tbl_AAA_User_remover` (`knnRemovedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_president` (`knnPresidentMemberID`),
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_vicePresident` (`knnVicePresidentMemberID`),
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_ozv1` (`knnOzv1MemberID`),
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_ozv2` (`knnOzv2MemberID`),
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_ozv3` (`knnOzv3MemberID`),
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_warden` (`knnWardenMemberID`),
  KEY `FK_tbl_MHA_Kanoon_tbl_MHA_Member_talker` (`knnTalkerMemberID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_AAA_User_creator` FOREIGN KEY (`knnCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_AAA_User_modifier` FOREIGN KEY (`knnUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_AAA_User_remover` FOREIGN KEY (`knnRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_ozv1` FOREIGN KEY (`knnOzv1MemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_ozv2` FOREIGN KEY (`knnOzv2MemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_ozv3` FOREIGN KEY (`knnOzv3MemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_president` FOREIGN KEY (`knnPresidentMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_talker` FOREIGN KEY (`knnTalkerMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_vicePresident` FOREIGN KEY (`knnVicePresidentMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`),
  CONSTRAINT `FK_tbl_MHA_Kanoon_tbl_MHA_Member_warden` FOREIGN KEY (`knnWardenMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MasterInsurer` (
  `minsID` int unsigned NOT NULL AUTO_INCREMENT,
  `minsName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minsStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `minsCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `minsCreatedBy` bigint unsigned DEFAULT NULL,
  `minsUpdatedAt` datetime DEFAULT NULL,
  `minsUpdatedBy` bigint unsigned DEFAULT NULL,
  `minsRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `minsRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`minsID`) USING BTREE,
  KEY `FK_tbl_MHA_Insurer_tbl_AAA_User_creator` (`minsCreatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Insurer_tbl_AAA_User_modifier` (`minsUpdatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_Insurer_tbl_AAA_User_remover` (`minsRemovedBy`) USING BTREE,
  CONSTRAINT `FK_tbl_MHA_MasterInsurer_tbl_AAA_User_creator` FOREIGN KEY (`minsCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MasterInsurer_tbl_AAA_User_modifier` FOREIGN KEY (`minsUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MasterInsurer_tbl_AAA_User_remover` FOREIGN KEY (`minsRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MasterInsurerType` (
  `minstypID` int unsigned NOT NULL AUTO_INCREMENT,
  `minstypMasterInsurerID` int unsigned NOT NULL,
  `minstypName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minstypStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `minstypCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `minstypCreatedBy` bigint unsigned DEFAULT NULL,
  `minstypUpdatedAt` datetime DEFAULT NULL,
  `minstypUpdatedBy` bigint unsigned DEFAULT NULL,
  `minstypRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `minstypRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`minstypID`) USING BTREE,
  KEY `FK_tbl_MHA_MasterInsurerType_tbl_AAA_User_creator` (`minstypCreatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_MasterInsurerType_tbl_AAA_User_modifier` (`minstypUpdatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_MasterInsurerType_tbl_AAA_User_remover` (`minstypRemovedBy`) USING BTREE,
  KEY `FK_tbl_MHA_MasterInsurerType_tbl_MHA_MasterInsurer` (`minstypMasterInsurerID`),
  CONSTRAINT `FK_tbl_MHA_MasterInsurerType_tbl_AAA_User_creator` FOREIGN KEY (`minstypCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MasterInsurerType_tbl_AAA_User_modifier` FOREIGN KEY (`minstypUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MasterInsurerType_tbl_AAA_User_remover` FOREIGN KEY (`minstypRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MasterInsurerType_tbl_MHA_MasterInsurer` FOREIGN KEY (`minstypMasterInsurerID`) REFERENCES `tbl_MHA_MasterInsurer` (`minsID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberMasterInsDoc` (
  `mbrminsdocID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrminsdocMemberID` bigint unsigned NOT NULL,
  `mbrminsdocDocNumber` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrminsdocDocDate` datetime DEFAULT NULL,
  `mbrminsdocStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, R:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember',
  `mbrminsdocCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrminsdocCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrminsdocUpdatedAt` datetime DEFAULT NULL,
  `mbrminsdocUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrminsdocRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrminsdocRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrminsdocID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMasterInsDoc_tbl_MHA_Member` (`mbrminsdocMemberID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMasterInsDoc_tbl_AAA_User_creator` (`mbrminsdocCreatedBy`),
  KEY `FK_tbl_MHA_MemberMasterInsDoc_tbl_AAA_User_modifer` (`mbrminsdocUpdatedBy`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsDoc_tbl_AAA_User_creator` FOREIGN KEY (`mbrminsdocCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsDoc_tbl_AAA_User_modifer` FOREIGN KEY (`mbrminsdocUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsDoc_tbl_MHA_Member` FOREIGN KEY (`mbrminsdocMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberMasterInsDocHistory` (
  `mbrminsdochstID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrminsdochstMasterInsDocID` bigint unsigned NOT NULL,
  `mbrminsdochstAction` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, R:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember',
  `mbrminsdochstCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrminsdochstCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrminsdochstUpdatedAt` datetime DEFAULT NULL,
  `mbrminsdochstUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrminsdochstRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrminsdochstRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrminsdochstID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMasterInsDocHistory_tbl_MHA_MemberMasterInsDoc` (`mbrminsdochstMasterInsDocID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMasterInsDocHistory_tbl_AAA_User_creator` (`mbrminsdochstCreatedBy`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsDocHistory_tbl_AAA_User_creator` FOREIGN KEY (`mbrminsdochstCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsDocHistory_tbl_MHA_MemberMasterInsDoc` FOREIGN KEY (`mbrminsdochstMasterInsDocID`) REFERENCES `tbl_MHA_MemberMasterInsDoc` (`mbrminsdocID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberMasterInsuranceHistory` (
  `mbrminshstID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrminshstMemberID` bigint unsigned NOT NULL,
  `mbrminshstMasterInsTypeID` int unsigned NOT NULL,
  `mbrminshstSubstation` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mbrminshstStartDate` date NOT NULL,
  `mbrminshstEndDate` date DEFAULT NULL,
  `mbrminshstInsuranceCode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrminshstCoCode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrminshstCoName` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrminshstIssuanceDate` datetime DEFAULT NULL,
  `mbrminshstCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrminshstCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrminshstUpdatedAt` datetime DEFAULT NULL,
  `mbrminshstUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrminshstRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrminshstRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrminshstID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMasterInsHistory_tbl_MHA_Member` (`mbrminshstMemberID`),
  KEY `FK_tbl_MHA_MemberMasterInsHistory_tbl_MHA_MasterInsurerType` (`mbrminshstMasterInsTypeID`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsHistory_tbl_MHA_MasterInsurerType` FOREIGN KEY (`mbrminshstMasterInsTypeID`) REFERENCES `tbl_MHA_MasterInsurerType` (`minstypID`),
  CONSTRAINT `FK_tbl_MHA_MemberMasterInsHistory_tbl_MHA_Member` FOREIGN KEY (`mbrminshstMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Membership` (
  `mshpID` int unsigned NOT NULL AUTO_INCREMENT,
  `mshpTitle` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mshpStartFrom` date NOT NULL,
  `mshpYearlyPrice` int unsigned NOT NULL,
  `mshpStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `mshpCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mshpCreatedBy` bigint unsigned DEFAULT NULL,
  `mshpUpdatedAt` datetime DEFAULT NULL,
  `mshpUpdatedBy` bigint unsigned DEFAULT NULL,
  `mshpRemovedAt` int unsigned DEFAULT '0',
  `mshpRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mshpID`),
  UNIQUE KEY `mshpStartFrom_mshpRemovedAt` (`mshpStartFrom`,`mshpRemovedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
ALTER TABLE {{%MHA_Membership}} AUTO_INCREMENT=10;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberMembership` (
  `mbrshpID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrshpMemberID` bigint unsigned NOT NULL,
  `mbrshpMembershipID` int unsigned DEFAULT NULL,
  `mbrshpStartDate` date NOT NULL,
  `mbrshpEndDate` date NOT NULL,
  `mbrshpStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'W' COMMENT 'W:WaitForPay, P:Paid',
  `mbrshpCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrshpCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrshpUpdatedAt` datetime DEFAULT NULL,
  `mbrshpUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrshpRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrshpRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrshpID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMembership_tbl_MHA_Member` (`mbrshpMemberID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberMembership_tbl_MHA_Membership` (`mbrshpMembershipID`),
  CONSTRAINT `FK_tbl_MHA_MemberMembership_tbl_MHA_Member` FOREIGN KEY (`mbrshpMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`) ON DELETE CASCADE,
  CONSTRAINT `FK_tbl_MHA_MemberMembership_tbl_MHA_Membership` FOREIGN KEY (`mbrshpMembershipID`) REFERENCES `tbl_MHA_Membership` (`mshpID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberSponsorship` (
  `mbrspsID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrspsMemberID` bigint unsigned NOT NULL,
  `mbrspsType` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'C:Child, F:Father, M:Mother, S:Spouse',
  `mbrspsShID` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspsSSN` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspsGender` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'M:Male, F:Female, N:Not Set',
  `mbrspsFirstName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mbrspsLastName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mbrspsFatherName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspsBirthDate` datetime DEFAULT NULL,
  `mbrspsBirthLocation` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspsMasterInsTypeID` int unsigned DEFAULT NULL,
  `mbrspsSubstation` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspsInsuranceCode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspsCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrspsCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrspsUpdatedAt` datetime DEFAULT NULL,
  `mbrspsUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrspsRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrspsRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrspsID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberSponsorship_tbl_MHA_Member` (`mbrspsMemberID`) USING BTREE,
  CONSTRAINT `FK_tbl_MHA_MemberSponsorship_tbl_MHA_Member` FOREIGN KEY (`mbrspsMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_SupplementaryInsurer` (
  `sinsID` int unsigned NOT NULL AUTO_INCREMENT,
  `sinsName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sinsStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `sinsCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sinsCreatedBy` bigint unsigned DEFAULT NULL,
  `sinsUpdatedAt` datetime DEFAULT NULL,
  `sinsUpdatedBy` bigint unsigned DEFAULT NULL,
  `sinsRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `sinsRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`sinsID`) USING BTREE,
  KEY `FK_tbl_MHA_SupplementaryInsurer_tbl_AAA_User_creator` (`sinsCreatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_SupplementaryInsurer_tbl_AAA_User_modifier` (`sinsUpdatedBy`) USING BTREE,
  KEY `FK_tbl_MHA_SupplementaryInsurer_tbl_AAA_User_remover` (`sinsRemovedBy`) USING BTREE,
  CONSTRAINT `FK_tbl_MHA_SupplementaryInsurer_tbl_AAA_User_creator` FOREIGN KEY (`sinsCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_SupplementaryInsurer_tbl_AAA_User_modifier` FOREIGN KEY (`sinsUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_SupplementaryInsurer_tbl_AAA_User_remover` FOREIGN KEY (`sinsRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberSupplementaryInsDoc` (
  `mbrsinsdocID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrsinsdocMemberID` bigint unsigned NOT NULL,
  `mbrsinsdocSupplementaryInsurerID` int unsigned NOT NULL,
  `mbrsinsdocDocNumber` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrsinsdocDocDate` datetime DEFAULT NULL,
  `mbrsinsdocStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, R:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember',
  `mbrsinsdocCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrsinsdocCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrsinsdocUpdatedAt` datetime DEFAULT NULL,
  `mbrsinsdocUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrsinsdocRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrsinsdocRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrsinsdocID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberSupplementaryInsDoc_tbl_MHA_Member` (`mbrsinsdocMemberID`),
  KEY `FK_tbl_MHA_MemberSuppInsDoc_tbl_MHA_SuppInsurer` (`mbrsinsdocSupplementaryInsurerID`),
  KEY `FK_tbl_MHA_MemberSupplementaryInsDoc_tbl_AAA_User_creator` (`mbrsinsdocCreatedBy`),
  KEY `FK_tbl_MHA_MemberSupplementaryInsDoc_tbl_AAA_User_modifier` (`mbrsinsdocUpdatedBy`),
  CONSTRAINT `FK_tbl_MHA_MemberSuppInsDoc_tbl_MHA_SuppInsurer` FOREIGN KEY (`mbrsinsdocSupplementaryInsurerID`) REFERENCES `tbl_MHA_SupplementaryInsurer` (`sinsID`),
  CONSTRAINT `FK_tbl_MHA_MemberSupplementaryInsDoc_tbl_AAA_User_creator` FOREIGN KEY (`mbrsinsdocCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MemberSupplementaryInsDoc_tbl_AAA_User_modifier` FOREIGN KEY (`mbrsinsdocUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MemberSupplementaryInsDoc_tbl_MHA_Member` FOREIGN KEY (`mbrsinsdocMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_MemberSupplementaryInsDocHistory` (
  `mbrsinsdochstID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrsinsdochstSupplementaryInsDocID` bigint unsigned NOT NULL,
  `mbrsinsdochstAction` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W' COMMENT 'W:WaitForSurvey, A:Accepted, R:Rejected, F:WaitForDocument, D:Documented, L:DocumentDeliveredToMember',
  `mbrsinsdochstCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrsinsdochstCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrsinsdochstUpdatedAt` datetime DEFAULT NULL,
  `mbrsinsdochstUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrsinsdochstRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrsinsdochstRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrsinsdochstID`) USING BTREE,
  KEY `FK_tbl_MHA_MemberSuppInsDocHistory_tbl_MHA_MemberSuppInsDoc` (`mbrsinsdochstSupplementaryInsDocID`),
  KEY `FK_tbl_MHA_MemberSuppInsDocHistory_tbl_AAA_User_creator` (`mbrsinsdochstCreatedBy`),
  CONSTRAINT `FK_tbl_MHA_MemberSuppInsDocHistory_tbl_AAA_User_creator` FOREIGN KEY (`mbrsinsdochstCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_MemberSuppInsDocHistory_tbl_MHA_MemberSuppInsDoc` FOREIGN KEY (`mbrsinsdochstSupplementaryInsDocID`) REFERENCES `tbl_MHA_MemberSupplementaryInsDoc` (`mbrsinsdocID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Member_Document` (
  `mbrdocID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrdocMemberID` bigint unsigned NOT NULL,
  `mbrdocDocumentID` int unsigned NOT NULL,
  `mbrdocFileID` bigint unsigned NOT NULL,
  `mbrdocStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W' COMMENT 'W:Wait For Approve, A:Approved',
  `mbrdocCreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `mbrdocCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrdocUpdatedAt` datetime DEFAULT NULL,
  `mbrdocUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrdocRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrdocRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrdocID`),
  KEY `FK_tbl_MHA_Member_Document_tbl_MHA_Member` (`mbrdocMemberID`),
  KEY `FK_tbl_MHA_Member_Document_tbl_MHA_Document` (`mbrdocDocumentID`),
  KEY `mbrdocCreatedAt` (`mbrdocCreatedAt`),
  CONSTRAINT `FK_tbl_MHA_Member_Document_tbl_MHA_Document` FOREIGN KEY (`mbrdocDocumentID`) REFERENCES `tbl_MHA_Document` (`docID`),
  CONSTRAINT `FK_tbl_MHA_Member_Document_tbl_MHA_Member` FOREIGN KEY (`mbrdocMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Member_Kanoon` (
  `mbrknnID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mbrknnMemberID` bigint unsigned NOT NULL,
  `mbrknnKanoonID` int unsigned NOT NULL,
  `mbrknnMembershipDegree` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'C:Continuous, D:Dependent1, E:Dependent2, L:Lover, H:Honorary',
  `mbrknnStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S' COMMENT 'S:WaitForSend, W:WaitForSurvey, E:WaitForResurvey, Z:Azmoon, A:Accepted, R:Rejected',
  `mbrknnCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrknnCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrknnUpdatedAt` datetime DEFAULT NULL,
  `mbrknnUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrknnRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrknnRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrknnID`) USING BTREE,
  KEY `FK_tbl_MHA_Member_Kanoon_tbl_MHA_Kanoon` (`mbrknnKanoonID`) USING BTREE,
  KEY `FK_tbl_MHA_Member_Kanoon_tbl_MHA_Member` (`mbrknnMemberID`) USING BTREE,
  CONSTRAINT `FK_tbl_MHA_Member_Kanoon_tbl_MHA_Kanoon` FOREIGN KEY (`mbrknnKanoonID`) REFERENCES `tbl_MHA_Kanoon` (`knnID`),
  CONSTRAINT `FK_tbl_MHA_Member_Kanoon_tbl_MHA_Member` FOREIGN KEY (`mbrknnMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Specialty` (
  `spcID` int unsigned NOT NULL AUTO_INCREMENT,
  `spcRoot` int unsigned DEFAULT NULL,
  `spcLeft` int unsigned NOT NULL,
  `spcRight` int unsigned NOT NULL,
  `spcLevel` smallint unsigned NOT NULL,
  `spcName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `spcDesc` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `spcImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spcStatus` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed',
  `spcCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `spcCreatedBy` bigint unsigned DEFAULT NULL,
  `spcUpdatedAt` datetime DEFAULT NULL,
  `spcUpdatedBy` bigint unsigned DEFAULT NULL,
  `spcRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `spcRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`spcID`) USING BTREE,
  KEY `tbl_MHA_Specialty_NK1` (`spcRoot`) USING BTREE,
  KEY `tbl_MHA_Specialty_NK2` (`spcLeft`) USING BTREE,
  KEY `tbl_MHA_Specialty_NK3` (`spcRight`) USING BTREE,
  KEY `tbl_MHA_Specialty_NK4` (`spcLevel`) USING BTREE,
  KEY `FK_tbl_MHA_Specialty_tbl_AAA_User_creator` (`spcCreatedBy`),
  KEY `FK_tbl_MHA_Specialty_tbl_AAA_User_modifier` (`spcUpdatedBy`),
  KEY `FK_tbl_MHA_Specialty_tbl_AAA_User_remover` (`spcRemovedBy`),
  CONSTRAINT `FK_tbl_MHA_Specialty_tbl_AAA_User_creator` FOREIGN KEY (`spcCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Specialty_tbl_AAA_User_modifier` FOREIGN KEY (`spcUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`),
  CONSTRAINT `FK_tbl_MHA_Specialty_tbl_AAA_User_remover` FOREIGN KEY (`spcRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

    $this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `tbl_MHA_Member_Specialty` (
  `mbrspcMemberID` bigint unsigned NOT NULL,
  `mbrspcSpecialtyID` int unsigned NOT NULL,
  `mbrspcDesc` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mbrspcCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mbrspcCreatedBy` bigint unsigned DEFAULT NULL,
  `mbrspcUpdatedAt` datetime DEFAULT NULL,
  `mbrspcUpdatedBy` bigint unsigned DEFAULT NULL,
  `mbrspcRemovedAt` int unsigned NOT NULL DEFAULT '0',
  `mbrspcRemovedBy` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`mbrspcMemberID`,`mbrspcSpecialtyID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
		);

		$this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberMasterInsDoc_after_insert` AFTER INSERT ON `tbl_MHA_MemberMasterInsDoc` FOR EACH ROW BEGIN
	INSERT INTO tbl_MHA_MemberMasterInsDocHistory(
		mbrminsdochstMasterInsDocID,
		mbrminsdochstAction,
		mbrminsdochstCreatedAt,
		mbrminsdochstCreatedBy
	) VALUES (
		NEW.mbrminsdocID,
		NEW.mbrminsdocStatus,
		NOW(),
		NEW.mbrminsdocCreatedBy
	);
END ;
SQL
		);

		$this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberMasterInsDoc_after_update` AFTER UPDATE ON `tbl_MHA_MemberMasterInsDoc` FOR EACH ROW BEGIN
	IF (NEW.mbrminsdocStatus != OLD.mbrminsdocStatus) THEN
		INSERT INTO tbl_MHA_MemberMasterInsDocHistory(
			mbrminsdochstMasterInsDocID,
			mbrminsdochstAction,
			mbrminsdochstCreatedAt,
			mbrminsdochstCreatedBy
		) VALUES (
			NEW.mbrminsdocID,
			NEW.mbrminsdocStatus,
			NOW(),
			NEW.mbrminsdocUpdatedBy
		);
	END IF;
END ;
SQL
		);

		$this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberSupplementaryInsDoc_after_insert` AFTER INSERT ON `tbl_MHA_MemberSupplementaryInsDoc` FOR EACH ROW BEGIN
	INSERT INTO tbl_MHA_MemberSupplementaryInsDocHistory(
		mbrsinsdochstSupplementaryInsDocID,
		mbrsinsdochstAction,
		mbrsinsdochstCreatedAt,
		mbrsinsdochstCreatedBy
	) VALUES (
		NEW.mbrsinsdocID,
		NEW.mbrsinsdocStatus,
		NOW(),
		NEW.mbrsinsdocCreatedBy
	);
END ;
SQL
		);

		$this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberSupplementaryInsDoc_after_update` AFTER UPDATE ON `tbl_MHA_MemberSupplementaryInsDoc` FOR EACH ROW BEGIN
	IF (NEW.mbrsinsdocStatus != OLD.mbrsinsdocStatus) THEN
		INSERT INTO tbl_MHA_MemberSupplementaryInsDocHistory(
			mbrsinsdochstSupplementaryInsDocID,
			mbrsinsdochstAction,
			mbrsinsdochstCreatedAt,
			mbrsinsdochstCreatedBy
		) VALUES (
			NEW.mbrsinsdocID,
			NEW.mbrsinsdocStatus,
			NOW(),
			NEW.mbrsinsdocUpdatedBy
		);
	END IF;
END ;
SQL
		);

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Document` AFTER UPDATE ON `tbl_MHA_Document` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.docName) != ISNULL(NEW.docName) OR OLD.docName != NEW.docName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docName", IF(ISNULL(OLD.docName), NULL, OLD.docName))); END IF;
  IF ISNULL(OLD.docType) != ISNULL(NEW.docType) OR OLD.docType != NEW.docType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("docType", IF(ISNULL(OLD.docType), NULL, OLD.docType))); END IF;
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
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Kanoon` AFTER UPDATE ON `tbl_MHA_Kanoon` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.knnName) != ISNULL(NEW.knnName) OR OLD.knnName != NEW.knnName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnName", IF(ISNULL(OLD.knnName), NULL, OLD.knnName))); END IF;
  IF ISNULL(OLD.knnPresidentMemberID) != ISNULL(NEW.knnPresidentMemberID) OR OLD.knnPresidentMemberID != NEW.knnPresidentMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnPresidentMemberID", IF(ISNULL(OLD.knnPresidentMemberID), NULL, OLD.knnPresidentMemberID))); END IF;
  IF ISNULL(OLD.knnVicePresidentMemberID) != ISNULL(NEW.knnVicePresidentMemberID) OR OLD.knnVicePresidentMemberID != NEW.knnVicePresidentMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnVicePresidentMemberID", IF(ISNULL(OLD.knnVicePresidentMemberID), NULL, OLD.knnVicePresidentMemberID))); END IF;
  IF ISNULL(OLD.knnOzv1MemberID) != ISNULL(NEW.knnOzv1MemberID) OR OLD.knnOzv1MemberID != NEW.knnOzv1MemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnOzv1MemberID", IF(ISNULL(OLD.knnOzv1MemberID), NULL, OLD.knnOzv1MemberID))); END IF;
  IF ISNULL(OLD.knnOzv2MemberID) != ISNULL(NEW.knnOzv2MemberID) OR OLD.knnOzv2MemberID != NEW.knnOzv2MemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnOzv2MemberID", IF(ISNULL(OLD.knnOzv2MemberID), NULL, OLD.knnOzv2MemberID))); END IF;
  IF ISNULL(OLD.knnOzv3MemberID) != ISNULL(NEW.knnOzv3MemberID) OR OLD.knnOzv3MemberID != NEW.knnOzv3MemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnOzv3MemberID", IF(ISNULL(OLD.knnOzv3MemberID), NULL, OLD.knnOzv3MemberID))); END IF;
  IF ISNULL(OLD.knnWardenMemberID) != ISNULL(NEW.knnWardenMemberID) OR OLD.knnWardenMemberID != NEW.knnWardenMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnWardenMemberID", IF(ISNULL(OLD.knnWardenMemberID), NULL, OLD.knnWardenMemberID))); END IF;
  IF ISNULL(OLD.knnTalkerMemberID) != ISNULL(NEW.knnTalkerMemberID) OR OLD.knnTalkerMemberID != NEW.knnTalkerMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnTalkerMemberID", IF(ISNULL(OLD.knnTalkerMemberID), NULL, OLD.knnTalkerMemberID))); END IF;
  IF ISNULL(OLD.knnStatus) != ISNULL(NEW.knnStatus) OR OLD.knnStatus != NEW.knnStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("knnStatus", IF(ISNULL(OLD.knnStatus), NULL, OLD.knnStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.knnUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.knnUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_Kanoon"
         , atlInfo   = JSON_OBJECT("knnID", OLD.knnID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MasterInsurer` AFTER UPDATE ON `tbl_MHA_MasterInsurer` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.minsName) != ISNULL(NEW.minsName) OR OLD.minsName != NEW.minsName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("minsName", IF(ISNULL(OLD.minsName), NULL, OLD.minsName))); END IF;
  IF ISNULL(OLD.minsStatus) != ISNULL(NEW.minsStatus) OR OLD.minsStatus != NEW.minsStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("minsStatus", IF(ISNULL(OLD.minsStatus), NULL, OLD.minsStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.minsUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.minsUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MasterInsurer"
         , atlInfo   = JSON_OBJECT("minsID", OLD.minsID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MasterInsurerType` AFTER UPDATE ON `tbl_MHA_MasterInsurerType` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.minstypMasterInsurerID) != ISNULL(NEW.minstypMasterInsurerID) OR OLD.minstypMasterInsurerID != NEW.minstypMasterInsurerID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("minstypMasterInsurerID", IF(ISNULL(OLD.minstypMasterInsurerID), NULL, OLD.minstypMasterInsurerID))); END IF;
  IF ISNULL(OLD.minstypName) != ISNULL(NEW.minstypName) OR OLD.minstypName != NEW.minstypName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("minstypName", IF(ISNULL(OLD.minstypName), NULL, OLD.minstypName))); END IF;
  IF ISNULL(OLD.minstypStatus) != ISNULL(NEW.minstypStatus) OR OLD.minstypStatus != NEW.minstypStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("minstypStatus", IF(ISNULL(OLD.minstypStatus), NULL, OLD.minstypStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.minstypUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.minstypUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MasterInsurerType"
         , atlInfo   = JSON_OBJECT("minstypID", OLD.minstypID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Member` AFTER UPDATE ON `tbl_MHA_Member` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrRegisterCode) != ISNULL(NEW.mbrRegisterCode) OR OLD.mbrRegisterCode != NEW.mbrRegisterCode THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrRegisterCode", IF(ISNULL(OLD.mbrRegisterCode), NULL, OLD.mbrRegisterCode))); END IF;
  IF ISNULL(OLD.mbrAcceptedAt) != ISNULL(NEW.mbrAcceptedAt) OR OLD.mbrAcceptedAt != NEW.mbrAcceptedAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrAcceptedAt", IF(ISNULL(OLD.mbrAcceptedAt), NULL, OLD.mbrAcceptedAt))); END IF;
  IF ISNULL(OLD.mbrMusicExperiences) != ISNULL(NEW.mbrMusicExperiences) OR OLD.mbrMusicExperiences != NEW.mbrMusicExperiences THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrMusicExperiences", IF(ISNULL(OLD.mbrMusicExperiences), NULL, OLD.mbrMusicExperiences))); END IF;
  IF ISNULL(OLD.mbrMusicExperienceStartAt) != ISNULL(NEW.mbrMusicExperienceStartAt) OR OLD.mbrMusicExperienceStartAt != NEW.mbrMusicExperienceStartAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrMusicExperienceStartAt", IF(ISNULL(OLD.mbrMusicExperienceStartAt), NULL, OLD.mbrMusicExperienceStartAt))); END IF;
  IF ISNULL(OLD.mbrArtHistory) != ISNULL(NEW.mbrArtHistory) OR OLD.mbrArtHistory != NEW.mbrArtHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrArtHistory", IF(ISNULL(OLD.mbrArtHistory), NULL, OLD.mbrArtHistory))); END IF;
  IF ISNULL(OLD.mbrMusicEducationHistory) != ISNULL(NEW.mbrMusicEducationHistory) OR OLD.mbrMusicEducationHistory != NEW.mbrMusicEducationHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrMusicEducationHistory", IF(ISNULL(OLD.mbrMusicEducationHistory), NULL, OLD.mbrMusicEducationHistory))); END IF;
  IF ISNULL(OLD.mbrStatus) != ISNULL(NEW.mbrStatus) OR OLD.mbrStatus != NEW.mbrStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrStatus", IF(ISNULL(OLD.mbrStatus), NULL, OLD.mbrStatus))); END IF;

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
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberMasterInsDoc` AFTER UPDATE ON `tbl_MHA_MemberMasterInsDoc` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrminsdocMemberID) != ISNULL(NEW.mbrminsdocMemberID) OR OLD.mbrminsdocMemberID != NEW.mbrminsdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocMemberID", IF(ISNULL(OLD.mbrminsdocMemberID), NULL, OLD.mbrminsdocMemberID))); END IF;
  IF ISNULL(OLD.mbrminsdocDocNumber) != ISNULL(NEW.mbrminsdocDocNumber) OR OLD.mbrminsdocDocNumber != NEW.mbrminsdocDocNumber THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocDocNumber", IF(ISNULL(OLD.mbrminsdocDocNumber), NULL, OLD.mbrminsdocDocNumber))); END IF;
  IF ISNULL(OLD.mbrminsdocDocDate) != ISNULL(NEW.mbrminsdocDocDate) OR OLD.mbrminsdocDocDate != NEW.mbrminsdocDocDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocDocDate", IF(ISNULL(OLD.mbrminsdocDocDate), NULL, OLD.mbrminsdocDocDate))); END IF;
  IF ISNULL(OLD.mbrminsdocStatus) != ISNULL(NEW.mbrminsdocStatus) OR OLD.mbrminsdocStatus != NEW.mbrminsdocStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocStatus", IF(ISNULL(OLD.mbrminsdocStatus), NULL, OLD.mbrminsdocStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrminsdocUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrminsdocUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberMasterInsDoc"
         , atlInfo   = JSON_OBJECT("mbrminsdocID", OLD.mbrminsdocID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberMasterInsDocHistory` AFTER UPDATE ON `tbl_MHA_MemberMasterInsDocHistory` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrminsdochstMasterInsDocID) != ISNULL(NEW.mbrminsdochstMasterInsDocID) OR OLD.mbrminsdochstMasterInsDocID != NEW.mbrminsdochstMasterInsDocID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdochstMasterInsDocID", IF(ISNULL(OLD.mbrminsdochstMasterInsDocID), NULL, OLD.mbrminsdochstMasterInsDocID))); END IF;
  IF ISNULL(OLD.mbrminsdochstAction) != ISNULL(NEW.mbrminsdochstAction) OR OLD.mbrminsdochstAction != NEW.mbrminsdochstAction THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdochstAction", IF(ISNULL(OLD.mbrminsdochstAction), NULL, OLD.mbrminsdochstAction))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrminsdochstUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrminsdochstUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberMasterInsDocHistory"
         , atlInfo   = JSON_OBJECT("mbrminsdochstID", OLD.mbrminsdochstID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberMasterInsuranceHistory` AFTER UPDATE ON `tbl_MHA_MemberMasterInsuranceHistory` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrminshstMemberID) != ISNULL(NEW.mbrminshstMemberID) OR OLD.mbrminshstMemberID != NEW.mbrminshstMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstMemberID", IF(ISNULL(OLD.mbrminshstMemberID), NULL, OLD.mbrminshstMemberID))); END IF;
  IF ISNULL(OLD.mbrminshstMasterInsTypeID) != ISNULL(NEW.mbrminshstMasterInsTypeID) OR OLD.mbrminshstMasterInsTypeID != NEW.mbrminshstMasterInsTypeID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstMasterInsTypeID", IF(ISNULL(OLD.mbrminshstMasterInsTypeID), NULL, OLD.mbrminshstMasterInsTypeID))); END IF;
  IF ISNULL(OLD.mbrminshstSubstation) != ISNULL(NEW.mbrminshstSubstation) OR OLD.mbrminshstSubstation != NEW.mbrminshstSubstation THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstSubstation", IF(ISNULL(OLD.mbrminshstSubstation), NULL, OLD.mbrminshstSubstation))); END IF;
  IF ISNULL(OLD.mbrminshstStartDate) != ISNULL(NEW.mbrminshstStartDate) OR OLD.mbrminshstStartDate != NEW.mbrminshstStartDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstStartDate", IF(ISNULL(OLD.mbrminshstStartDate), NULL, OLD.mbrminshstStartDate))); END IF;
  IF ISNULL(OLD.mbrminshstEndDate) != ISNULL(NEW.mbrminshstEndDate) OR OLD.mbrminshstEndDate != NEW.mbrminshstEndDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstEndDate", IF(ISNULL(OLD.mbrminshstEndDate), NULL, OLD.mbrminshstEndDate))); END IF;
  IF ISNULL(OLD.mbrminshstInsuranceCode) != ISNULL(NEW.mbrminshstInsuranceCode) OR OLD.mbrminshstInsuranceCode != NEW.mbrminshstInsuranceCode THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstInsuranceCode", IF(ISNULL(OLD.mbrminshstInsuranceCode), NULL, OLD.mbrminshstInsuranceCode))); END IF;
  IF ISNULL(OLD.mbrminshstCoCode) != ISNULL(NEW.mbrminshstCoCode) OR OLD.mbrminshstCoCode != NEW.mbrminshstCoCode THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstCoCode", IF(ISNULL(OLD.mbrminshstCoCode), NULL, OLD.mbrminshstCoCode))); END IF;
  IF ISNULL(OLD.mbrminshstCoName) != ISNULL(NEW.mbrminshstCoName) OR OLD.mbrminshstCoName != NEW.mbrminshstCoName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstCoName", IF(ISNULL(OLD.mbrminshstCoName), NULL, OLD.mbrminshstCoName))); END IF;
  IF ISNULL(OLD.mbrminshstIssuanceDate) != ISNULL(NEW.mbrminshstIssuanceDate) OR OLD.mbrminshstIssuanceDate != NEW.mbrminshstIssuanceDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminshstIssuanceDate", IF(ISNULL(OLD.mbrminshstIssuanceDate), NULL, OLD.mbrminshstIssuanceDate))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrminshstUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrminshstUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberMasterInsuranceHistory"
         , atlInfo   = JSON_OBJECT("mbrminshstID", OLD.mbrminshstID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberMembership` AFTER UPDATE ON `tbl_MHA_MemberMembership` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrshpMemberID) != ISNULL(NEW.mbrshpMemberID) OR OLD.mbrshpMemberID != NEW.mbrshpMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrshpMemberID", IF(ISNULL(OLD.mbrshpMemberID), NULL, OLD.mbrshpMemberID))); END IF;
  IF ISNULL(OLD.mbrshpMembershipID) != ISNULL(NEW.mbrshpMembershipID) OR OLD.mbrshpMembershipID != NEW.mbrshpMembershipID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrshpMembershipID", IF(ISNULL(OLD.mbrshpMembershipID), NULL, OLD.mbrshpMembershipID))); END IF;
  IF ISNULL(OLD.mbrshpStartDate) != ISNULL(NEW.mbrshpStartDate) OR OLD.mbrshpStartDate != NEW.mbrshpStartDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrshpStartDate", IF(ISNULL(OLD.mbrshpStartDate), NULL, OLD.mbrshpStartDate))); END IF;
  IF ISNULL(OLD.mbrshpEndDate) != ISNULL(NEW.mbrshpEndDate) OR OLD.mbrshpEndDate != NEW.mbrshpEndDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrshpEndDate", IF(ISNULL(OLD.mbrshpEndDate), NULL, OLD.mbrshpEndDate))); END IF;
  IF ISNULL(OLD.mbrshpStatus) != ISNULL(NEW.mbrshpStatus) OR OLD.mbrshpStatus != NEW.mbrshpStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrshpStatus", IF(ISNULL(OLD.mbrshpStatus), NULL, OLD.mbrshpStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrshpUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrshpUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberMembership"
         , atlInfo   = JSON_OBJECT("mbrshpID", OLD.mbrshpID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Membership` AFTER UPDATE ON `tbl_MHA_Membership` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mshpTitle) != ISNULL(NEW.mshpTitle) OR OLD.mshpTitle != NEW.mshpTitle THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mshpTitle", IF(ISNULL(OLD.mshpTitle), NULL, OLD.mshpTitle))); END IF;
  IF ISNULL(OLD.mshpStartFrom) != ISNULL(NEW.mshpStartFrom) OR OLD.mshpStartFrom != NEW.mshpStartFrom THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mshpStartFrom", IF(ISNULL(OLD.mshpStartFrom), NULL, OLD.mshpStartFrom))); END IF;
  IF ISNULL(OLD.mshpYearlyPrice) != ISNULL(NEW.mshpYearlyPrice) OR OLD.mshpYearlyPrice != NEW.mshpYearlyPrice THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mshpYearlyPrice", IF(ISNULL(OLD.mshpYearlyPrice), NULL, OLD.mshpYearlyPrice))); END IF;
  IF ISNULL(OLD.mshpStatus) != ISNULL(NEW.mshpStatus) OR OLD.mshpStatus != NEW.mshpStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mshpStatus", IF(ISNULL(OLD.mshpStatus), NULL, OLD.mshpStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mshpUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mshpUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_Membership"
         , atlInfo   = JSON_OBJECT("mshpID", OLD.mshpID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberSponsorship` AFTER UPDATE ON `tbl_MHA_MemberSponsorship` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrspsMemberID) != ISNULL(NEW.mbrspsMemberID) OR OLD.mbrspsMemberID != NEW.mbrspsMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsMemberID", IF(ISNULL(OLD.mbrspsMemberID), NULL, OLD.mbrspsMemberID))); END IF;
  IF ISNULL(OLD.mbrspsType) != ISNULL(NEW.mbrspsType) OR OLD.mbrspsType != NEW.mbrspsType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsType", IF(ISNULL(OLD.mbrspsType), NULL, OLD.mbrspsType))); END IF;
  IF ISNULL(OLD.mbrspsShID) != ISNULL(NEW.mbrspsShID) OR OLD.mbrspsShID != NEW.mbrspsShID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsShID", IF(ISNULL(OLD.mbrspsShID), NULL, OLD.mbrspsShID))); END IF;
  IF ISNULL(OLD.mbrspsSSN) != ISNULL(NEW.mbrspsSSN) OR OLD.mbrspsSSN != NEW.mbrspsSSN THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsSSN", IF(ISNULL(OLD.mbrspsSSN), NULL, OLD.mbrspsSSN))); END IF;
  IF ISNULL(OLD.mbrspsGender) != ISNULL(NEW.mbrspsGender) OR OLD.mbrspsGender != NEW.mbrspsGender THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsGender", IF(ISNULL(OLD.mbrspsGender), NULL, OLD.mbrspsGender))); END IF;
  IF ISNULL(OLD.mbrspsFirstName) != ISNULL(NEW.mbrspsFirstName) OR OLD.mbrspsFirstName != NEW.mbrspsFirstName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsFirstName", IF(ISNULL(OLD.mbrspsFirstName), NULL, OLD.mbrspsFirstName))); END IF;
  IF ISNULL(OLD.mbrspsLastName) != ISNULL(NEW.mbrspsLastName) OR OLD.mbrspsLastName != NEW.mbrspsLastName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsLastName", IF(ISNULL(OLD.mbrspsLastName), NULL, OLD.mbrspsLastName))); END IF;
  IF ISNULL(OLD.mbrspsFatherName) != ISNULL(NEW.mbrspsFatherName) OR OLD.mbrspsFatherName != NEW.mbrspsFatherName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsFatherName", IF(ISNULL(OLD.mbrspsFatherName), NULL, OLD.mbrspsFatherName))); END IF;
  IF ISNULL(OLD.mbrspsBirthDate) != ISNULL(NEW.mbrspsBirthDate) OR OLD.mbrspsBirthDate != NEW.mbrspsBirthDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsBirthDate", IF(ISNULL(OLD.mbrspsBirthDate), NULL, OLD.mbrspsBirthDate))); END IF;
  IF ISNULL(OLD.mbrspsBirthLocation) != ISNULL(NEW.mbrspsBirthLocation) OR OLD.mbrspsBirthLocation != NEW.mbrspsBirthLocation THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsBirthLocation", IF(ISNULL(OLD.mbrspsBirthLocation), NULL, OLD.mbrspsBirthLocation))); END IF;
  IF ISNULL(OLD.mbrspsMasterInsTypeID) != ISNULL(NEW.mbrspsMasterInsTypeID) OR OLD.mbrspsMasterInsTypeID != NEW.mbrspsMasterInsTypeID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsMasterInsTypeID", IF(ISNULL(OLD.mbrspsMasterInsTypeID), NULL, OLD.mbrspsMasterInsTypeID))); END IF;
  IF ISNULL(OLD.mbrspsSubstation) != ISNULL(NEW.mbrspsSubstation) OR OLD.mbrspsSubstation != NEW.mbrspsSubstation THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsSubstation", IF(ISNULL(OLD.mbrspsSubstation), NULL, OLD.mbrspsSubstation))); END IF;
  IF ISNULL(OLD.mbrspsInsuranceCode) != ISNULL(NEW.mbrspsInsuranceCode) OR OLD.mbrspsInsuranceCode != NEW.mbrspsInsuranceCode THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspsInsuranceCode", IF(ISNULL(OLD.mbrspsInsuranceCode), NULL, OLD.mbrspsInsuranceCode))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrspsUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrspsUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberSponsorship"
         , atlInfo   = JSON_OBJECT("mbrspsID", OLD.mbrspsID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberSupplementaryInsDoc` AFTER UPDATE ON `tbl_MHA_MemberSupplementaryInsDoc` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrsinsdocMemberID) != ISNULL(NEW.mbrsinsdocMemberID) OR OLD.mbrsinsdocMemberID != NEW.mbrsinsdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocMemberID", IF(ISNULL(OLD.mbrsinsdocMemberID), NULL, OLD.mbrsinsdocMemberID))); END IF;
  IF ISNULL(OLD.mbrsinsdocSupplementaryInsurerID) != ISNULL(NEW.mbrsinsdocSupplementaryInsurerID) OR OLD.mbrsinsdocSupplementaryInsurerID != NEW.mbrsinsdocSupplementaryInsurerID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocSupplementaryInsurerID", IF(ISNULL(OLD.mbrsinsdocSupplementaryInsurerID), NULL, OLD.mbrsinsdocSupplementaryInsurerID))); END IF;
  IF ISNULL(OLD.mbrsinsdocDocNumber) != ISNULL(NEW.mbrsinsdocDocNumber) OR OLD.mbrsinsdocDocNumber != NEW.mbrsinsdocDocNumber THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocDocNumber", IF(ISNULL(OLD.mbrsinsdocDocNumber), NULL, OLD.mbrsinsdocDocNumber))); END IF;
  IF ISNULL(OLD.mbrsinsdocDocDate) != ISNULL(NEW.mbrsinsdocDocDate) OR OLD.mbrsinsdocDocDate != NEW.mbrsinsdocDocDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocDocDate", IF(ISNULL(OLD.mbrsinsdocDocDate), NULL, OLD.mbrsinsdocDocDate))); END IF;
  IF ISNULL(OLD.mbrsinsdocStatus) != ISNULL(NEW.mbrsinsdocStatus) OR OLD.mbrsinsdocStatus != NEW.mbrsinsdocStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocStatus", IF(ISNULL(OLD.mbrsinsdocStatus), NULL, OLD.mbrsinsdocStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrsinsdocUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrsinsdocUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberSupplementaryInsDoc"
         , atlInfo   = JSON_OBJECT("mbrsinsdocID", OLD.mbrsinsdocID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_MemberSupplementaryInsDocHistory` AFTER UPDATE ON `tbl_MHA_MemberSupplementaryInsDocHistory` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrsinsdochstSupplementaryInsDocID) != ISNULL(NEW.mbrsinsdochstSupplementaryInsDocID) OR OLD.mbrsinsdochstSupplementaryInsDocID != NEW.mbrsinsdochstSupplementaryInsDocID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdochstSupplementaryInsDocID", IF(ISNULL(OLD.mbrsinsdochstSupplementaryInsDocID), NULL, OLD.mbrsinsdochstSupplementaryInsDocID))); END IF;
  IF ISNULL(OLD.mbrsinsdochstAction) != ISNULL(NEW.mbrsinsdochstAction) OR OLD.mbrsinsdochstAction != NEW.mbrsinsdochstAction THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdochstAction", IF(ISNULL(OLD.mbrsinsdochstAction), NULL, OLD.mbrsinsdochstAction))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrsinsdochstUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrsinsdochstUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_MemberSupplementaryInsDocHistory"
         , atlInfo   = JSON_OBJECT("mbrsinsdochstID", OLD.mbrsinsdochstID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Member_Document` AFTER UPDATE ON `tbl_MHA_Member_Document` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrdocMemberID) != ISNULL(NEW.mbrdocMemberID) OR OLD.mbrdocMemberID != NEW.mbrdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocMemberID", IF(ISNULL(OLD.mbrdocMemberID), NULL, OLD.mbrdocMemberID))); END IF;
  IF ISNULL(OLD.mbrdocDocumentID) != ISNULL(NEW.mbrdocDocumentID) OR OLD.mbrdocDocumentID != NEW.mbrdocDocumentID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocDocumentID", IF(ISNULL(OLD.mbrdocDocumentID), NULL, OLD.mbrdocDocumentID))); END IF;
  IF ISNULL(OLD.mbrdocFileID) != ISNULL(NEW.mbrdocFileID) OR OLD.mbrdocFileID != NEW.mbrdocFileID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocFileID", IF(ISNULL(OLD.mbrdocFileID), NULL, OLD.mbrdocFileID))); END IF;

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
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Member_Kanoon` AFTER UPDATE ON `tbl_MHA_Member_Kanoon` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrknnMemberID) != ISNULL(NEW.mbrknnMemberID) OR OLD.mbrknnMemberID != NEW.mbrknnMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnMemberID", IF(ISNULL(OLD.mbrknnMemberID), NULL, OLD.mbrknnMemberID))); END IF;
  IF ISNULL(OLD.mbrknnKanoonID) != ISNULL(NEW.mbrknnKanoonID) OR OLD.mbrknnKanoonID != NEW.mbrknnKanoonID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnKanoonID", IF(ISNULL(OLD.mbrknnKanoonID), NULL, OLD.mbrknnKanoonID))); END IF;
  IF ISNULL(OLD.mbrknnMembershipDegree) != ISNULL(NEW.mbrknnMembershipDegree) OR OLD.mbrknnMembershipDegree != NEW.mbrknnMembershipDegree THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnMembershipDegree", IF(ISNULL(OLD.mbrknnMembershipDegree), NULL, OLD.mbrknnMembershipDegree))); END IF;
  IF ISNULL(OLD.mbrknnStatus) != ISNULL(NEW.mbrknnStatus) OR OLD.mbrknnStatus != NEW.mbrknnStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnStatus", IF(ISNULL(OLD.mbrknnStatus), NULL, OLD.mbrknnStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrknnUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrknnUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_Member_Kanoon"
         , atlInfo   = JSON_OBJECT("mbrknnID", OLD.mbrknnID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Member_Specialty` AFTER UPDATE ON `tbl_MHA_Member_Specialty` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrspcDesc) != ISNULL(NEW.mbrspcDesc) OR OLD.mbrspcDesc != NEW.mbrspcDesc THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrspcDesc", IF(ISNULL(OLD.mbrspcDesc), NULL, OLD.mbrspcDesc))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrspcUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.mbrspcUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_Member_Specialty"
         , atlInfo   = JSON_OBJECT("mbrspcMemberID", OLD.mbrspcMemberID, "mbrspcSpecialtyID", OLD.mbrspcSpecialtyID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_Specialty` AFTER UPDATE ON `tbl_MHA_Specialty` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.spcRoot) != ISNULL(NEW.spcRoot) OR OLD.spcRoot != NEW.spcRoot THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcRoot", IF(ISNULL(OLD.spcRoot), NULL, OLD.spcRoot))); END IF;
  IF ISNULL(OLD.spcLeft) != ISNULL(NEW.spcLeft) OR OLD.spcLeft != NEW.spcLeft THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcLeft", IF(ISNULL(OLD.spcLeft), NULL, OLD.spcLeft))); END IF;
  IF ISNULL(OLD.spcRight) != ISNULL(NEW.spcRight) OR OLD.spcRight != NEW.spcRight THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcRight", IF(ISNULL(OLD.spcRight), NULL, OLD.spcRight))); END IF;
  IF ISNULL(OLD.spcLevel) != ISNULL(NEW.spcLevel) OR OLD.spcLevel != NEW.spcLevel THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcLevel", IF(ISNULL(OLD.spcLevel), NULL, OLD.spcLevel))); END IF;
  IF ISNULL(OLD.spcName) != ISNULL(NEW.spcName) OR OLD.spcName != NEW.spcName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcName", IF(ISNULL(OLD.spcName), NULL, OLD.spcName))); END IF;
  IF ISNULL(OLD.spcDesc) != ISNULL(NEW.spcDesc) OR OLD.spcDesc != NEW.spcDesc THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcDesc", IF(ISNULL(OLD.spcDesc), NULL, OLD.spcDesc))); END IF;
  IF ISNULL(OLD.spcImage) != ISNULL(NEW.spcImage) OR OLD.spcImage != NEW.spcImage THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcImage", IF(ISNULL(OLD.spcImage), NULL, OLD.spcImage))); END IF;
  IF ISNULL(OLD.spcStatus) != ISNULL(NEW.spcStatus) OR OLD.spcStatus != NEW.spcStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("spcStatus", IF(ISNULL(OLD.spcStatus), NULL, OLD.spcStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.spcUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.spcUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_Specialty"
         , atlInfo   = JSON_OBJECT("spcID", OLD.spcID, "old", Changes);
  END IF;
END ;
SQL
    );

    $this->execute(<<<SQL
CREATE TRIGGER `trg_updatelog_tbl_MHA_SupplementaryInsurer` AFTER UPDATE ON `tbl_MHA_SupplementaryInsurer` FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.sinsName) != ISNULL(NEW.sinsName) OR OLD.sinsName != NEW.sinsName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("sinsName", IF(ISNULL(OLD.sinsName), NULL, OLD.sinsName))); END IF;
  IF ISNULL(OLD.sinsStatus) != ISNULL(NEW.sinsStatus) OR OLD.sinsStatus != NEW.sinsStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("sinsStatus", IF(ISNULL(OLD.sinsStatus), NULL, OLD.sinsStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.sinsUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
       SET atlBy     = NEW.sinsUpdatedBy
         , atlAction = "UPDATE"
         , atlTarget = "tbl_MHA_SupplementaryInsurer"
         , atlInfo   = JSON_OBJECT("sinsID", OLD.sinsID, "old", Changes);
  END IF;
END ;
SQL
    );
	}

	public function down()
	{
		// $this->dropTable('{{%User}}');
	}

}
