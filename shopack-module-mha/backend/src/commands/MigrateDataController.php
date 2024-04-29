<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use Ramsey\Uuid\Uuid;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\StringHelper;
use shopack\base\common\classes\datetime\Jalali;
use shopack\base\common\helpers\PhoneHelper;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuWalletStatus;
use shopack\aaa\common\enums\enuVoucherType;
use shopack\aaa\common\enums\enuVoucherStatus;
use shopack\aaa\common\enums\enuOnlinePaymentStatus;
use shopack\aaa\common\enums\enuOfflinePaymentStatus;
use iranhmusic\shopack\mha\backend\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\common\enums\enuDocumentType;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;
use iranhmusic\shopack\mha\backend\models\SpecialtyModel;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use shopack\aaa\common\enums\enuUserEducationLevel;
use shopack\aaa\common\enums\enuUserMaritalStatus;
use shopack\aaa\common\enums\enuUserMilitaryStatus;
use shopack\base\common\accounting\enums\enuUserAssetStatus;

/*
USE dbiranhmusic_yii;

DELETE FROM tbl_MHA_MemberMasterInsuranceHistory;
DELETE FROM tbl_MHA_MemberMasterInsDoc;
DELETE FROM tbl_MHA_MasterInsurer;
DELETE FROM tbl_MHA_MasterInsurerType;
DELETE FROM tbl_MHA_MemberSupplementaryInsDoc;
DELETE FROM tbl_MHA_SupplementaryInsurer;
DELETE FROM tbl_MHA_Member_Specialty;
DELETE FROM tbl_MHA_Specialty;
DELETE FROM tbl_MHA_Member_Kanoon;
DELETE FROM tbl_MHA_Kanoon;
DELETE FROM tbl_MHA_Document;
-- DELETE FROM tbl_MHA_MemberMembership;
-- DELETE FROM tbl_MHA_Membership;
DELETE FROM tbl_MHA_Accounting_UserAsset WHERE uasActorID > 100;
DELETE FROM tbl_MHA_Member WHERE mbrUserID > 100;
DELETE FROM tbl_MHA_BasicDefinition;

UPDATE tbl_AAA_User
	SET tbl_AAA_User.usrCreatedBy = NULL
    , tbl_AAA_User.usrUpdatedBy = NULL
	WHERE usrID > 100;

-- user images and docs:
DELETE FROM tbl_MHA_Member_Document;
UPDATE tbl_AAA_User
	SET tbl_AAA_User.usrImageFileID = null
	WHERE usrID > 100;
DELETE FROM tbl_AAA_UploadFile WHERE tbl_AAA_UploadFile.uflOwnerUserID > 100;

DELETE tbl_AAA_WalletTransaction FROM tbl_AAA_WalletTransaction INNER JOIN tbl_AAA_Wallet ON tbl_AAA_Wallet.walID = tbl_AAA_WalletTransaction.wtrWalletID WHERE tbl_AAA_Wallet.walOwnerUserID > 100;
delete from tbl_AAA_OfflinePayment WHERE ofpOwnerUserID > 100;
DELETE FROM tbl_AAA_Wallet WHERE tbl_AAA_Wallet.walOwnerUserID > 100;

DELETE FROM tbl_AAA_Message WHERE tbl_AAA_Message.msgUserID > 100;
DELETE FROM tbl_AAA_ApprovalRequest WHERE tbl_AAA_ApprovalRequest.aprUserID > 100;
DELETE FROM tbl_AAA_ForgotPasswordRequest WHERE tbl_AAA_ForgotPasswordRequest.fprUserID > 100;

DELETE FROM tbl_MHA_Report;
DELETE FROM tbl_AAA_Voucher WHERE tbl_AAA_Voucher.vchOwnerUserID > 100;

DELETE FROM tbl_SYS_ActionLogs;

DELETE FROM tbl_AAA_User WHERE tbl_AAA_User.usrID > 100;

DELETE FROM tbl_AAA_GeoCityOrVillage;
DELETE FROM tbl_AAA_GeoState;

DELETE FROM tbl_convert;


----------------------------------------------------------

-- SMS:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii aaa/message/process-queue 2>&1 >>logs/aaa_message_process-queue.log

-- FILE:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii aaa/file/process-queue 200 2>&1 >>logs/aaa_file_process-queue.log

TEST(1) item:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii aaa/file/process-queue 1 2>&1 >>logs/aaa_file_process-queue.log

-- MIGRATE:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii migrate/up --interactive 0 2>&1 >>logs/migrate.log

-- CONVERT:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii mha/migrate-data/from-v2 2>&1 >>logs/mha-migrate-data-from-v2.log

SELECT uquStatus, COUNT(*) FROM tbl_AAA_UploadQueue GROUP BY uquStatus WITH ROLLUP;

SELECT * FROM tbl_convert;


SELECT * FROM tbl_AAA_UploadQueue uqu INNER JOIN tbl_AAA_UploadFile ufl ON ufl.uflID = uqu.uquID WHERE uquStatus = 'E'


----------------------------------------------------------
payments
----------------------------------------------------------
delete from tbl_MHA_MemberMembership;

update tbl_AAA_Wallet set walRemainedAmount = 0 where walOwnerUserID > 100;

delete tbl_AAA_WalletTransaction
	from tbl_AAA_WalletTransaction
	inner join tbl_AAA_Wallet
	on tbl_AAA_Wallet.walID = tbl_AAA_WalletTransaction.wtrWalletID
	where walOwnerUserID > 100;

delete from tbl_AAA_OfflinePayment WHERE ofpOwnerUserID > 100;

delete tbl_AAA_OnlinePayment
	from tbl_AAA_OnlinePayment
	INNER JOIN tbl_AAA_Voucher
	ON tbl_AAA_Voucher.vchID = tbl_AAA_OnlinePayment.onpVoucherID
	WHERE vchOwnerUserID > 100;

delete from tbl_AAA_Voucher where vchOwnerUserID > 100;

delete from tbl_convert where tableName in ('v2.tbl_billing');

----------------------------------------------------------
----- reset files ----------------------------------------
----------------------------------------------------------
1: delete from ARVAN

2: delete tmp/upload

3: mysql:
DELETE FROM tbl_MHA_Member_Document;
ALTER TABLE tbl_MHA_Member_Document AUTO_INCREMENT=1;

UPDATE tbl_AAA_User
	SET tbl_AAA_User.usrImageFileID = NULL
	WHERE tbl_AAA_User.usrImageFileID IS NOT NULL;

DELETE FROM tbl_AAA_UploadQueue;
ALTER TABLE tbl_AAA_UploadQueue AUTO_INCREMENT=1;

DELETE FROM tbl_AAA_UploadFile;
ALTER TABLE tbl_AAA_UploadFile AUTO_INCREMENT=1;

UPDATE tbl_AAA_Gateway
 	SET tbl_AAA_Gateway.gtwUsages = NULL
	WHERE tbl_AAA_Gateway.gtwPluginName = 'ArvanS3ObjectStorageGateway';

DELETE FROM tbl_convert
  WHERE tableName = 'v2.tbl_profile->user-image'
  OR tableName = 'v2.tbl_document';

4: run mha/migrate-data/from-v2

-------------------------
SELECT uquStatus, count(*)
FROM tbl_AAA_UploadQueue
group by uquStatus

*/

class MigrateDataController extends Controller
{
  public function log($message, $type='info')
  {
    echo "[" . date('Y/m/d H:i:s') . "][{$type}] {$message}\n";
  }

  public function trace($message)
  {
    $this->log($message, 'trace');
  }

  public function queryExecute($qry, $function, $line) {
    try {
      return Yii::$app->db->createCommand($qry)->execute();
    } catch (\Throwable $th) {
      $this->trace('** EXCEPTION: ' . $th->getMessage());
      $this->trace($qry);
      $this->trace($function . ':' . $line);
      throw $th;
    }
  }

  public function queryAll($qry, $function, $line) {
    try {
      return Yii::$app->db->createCommand($qry)->queryAll();
    } catch (\Throwable $th) {
      $this->trace('** EXCEPTION: ' . $th->getMessage());
      $this->trace($qry);
      $this->trace($function . ':' . $line);
      throw $th;
    }
  }

  public function queryOne($qry, $function, $line) {
    try {
      return Yii::$app->db->createCommand($qry)->queryOne();
    } catch (\Throwable $th) {
      $this->trace('** EXCEPTION: ' . $th->getMessage());
      $this->trace($qry);
      $this->trace($function . ':' . $line);
      throw $th;
    }
  }

  public function actionFromV2()
  {
    $this->log("migrating from v2");

    //unlock
    $fnUnlock = function() {
      $qry = "DELETE FROM tbl_convert WHERE tableName = 'locked'";
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
    };

    $convertTableData = $this->readConvertTable();
    if (isset($convertTableData['locked'])) {
      $nonExpireFound = false;
      foreach ($convertTableData as $tbl => $data) {
        if (($tbl != 'locked') && (($data['expired'] ?? 0) == 0)) {
          $nonExpireFound = true;
          break;
        }
      }

      if ($nonExpireFound) {
        $this->log("LOCKED");
        return;
      }

      $this->log("RE-LOCKING...");
      $qry = "UPDATE tbl_convert SET at=NOW() WHERE tableName='locked'";
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
    }

    //lock
    $qry = "INSERT IGNORE INTO tbl_convert(tableName, lastID) VALUES ('locked', 0)";
    $this->queryExecute($qry, __FUNCTION__, __LINE__);

    try {
      /*  1 */ $this->convert_categories_to_State($convertTableData);
      /*  1 */ $this->convert_categories_to_City($convertTableData);
      /*  2 */ // رشته تخصصی موسیقی
      /*  3 */ $this->convert_categories_to_MhaBDef($convertTableData, 3, 'instrument', enuBasicDefinitionType::Instrument);
      /*  4 */ // شغل
      /*  5 */ // مدرک تحصیلی
      /*  6 */ // رشته تحصیلی
      /*  7 */ // نوع بیمه
      /*  8 */ $this->convert_categories_to_MhaBDef($convertTableData, 8, 'sing', enuBasicDefinitionType::Sing);
      /*  9 */ $this->convert_categories_to_MhaBDef($convertTableData, 9, 'research', enuBasicDefinitionType::Research);
      /* 10 */ $this->convert_categories_to_Document($convertTableData);

      $this->convert_club_to_Kanoon($convertTableData);

      $this->convert_profile_to_User($convertTableData);

      $this->convert_profile_to_Member($convertTableData);

      $this->convert_expert_to_Mbr_Specialty($convertTableData);

      $this->convert_billing($convertTableData);

      // NOT COMPLETED $this->convert_onlinebank($convertTableData);

      $this->convert_profile_to_Mbr_Kanoon($convertTableData);

      $this->convert_profile_to_Usr_other_1($convertTableData);

      $this->convert_profile_to_Mbr_other_1($convertTableData);

      // $this->copylostimages();
      $this->convert_profile_to_UserImage($convertTableData);
      $this->convert_document_to_Mbr_Document($convertTableData);

      $this->convert_create_default_password_for_members($convertTableData);

      $this->convert_update_members_expiredate($convertTableData);

      $this->convert_profile_to_Mbr_expiredate($convertTableData);





      $fnUnlock();

    } catch (\Throwable $exp) {
      $fnUnlock();
      $this->log($exp->getMessage());
      throw $exp;
    } catch (\Exception $exp) {
      $fnUnlock();
      $this->log($exp->getMessage());
      throw $exp;
    }
  }

  public function readConvertTable()
  {
    $tableSchema = Yii::$app->db
      ->getSchema()
      ->getTableSchema('tbl_convert');

    if ($tableSchema === null) {
      $qry =<<<SQL
CREATE TABLE `tbl_convert` (
	`tableName` VARCHAR(256) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`lastID` BIGINT(20) UNSIGNED NOT NULL,
	`at` DATETIME NOT NULL DEFAULT (NOW()),
	`info` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	PRIMARY KEY (`tableName`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQL;

      $this->queryExecute($qry, __FUNCTION__, __LINE__);
      return [];
    }

    $result = [];
    $qry =<<<SQL
  SELECT tbl_convert.*
       , tbl_convert.at < DATE_SUB(NOW(), INTERVAL 2 MINUTE) AS expired
    FROM tbl_convert
SQL;
    $rows = $this->queryAll($qry, __FUNCTION__, __LINE__);
    foreach ($rows as $row) {
      $result[$row['tableName']] = $row;
    }
    return $result;
  }

  function putData(
    $destTableName,
    $destTableFields,
    $values,
    $lastID,
    $convertKey,
    $onUpdateFields = null
  ) {
    $this->log("{$lastID}...");

    $transaction = Yii::$app->db->beginTransaction();

    try {
      $destTableFields = implode(',', $destTableFields);

      //destination table
      $qry =<<<SQL
      INSERT INTO {$destTableName}({$destTableFields})
      VALUES
SQL;
      if (is_array($values))
        $qry .= '(' . implode('),(', $values) . ')';
      else
        $qry .= '(' . $values . ')';

      if (empty($onUpdateFields) == false) {
        $fieldsString = [];
        foreach ($onUpdateFields as $fld) {
          $fieldsString[] = "{$fld} = VALUES({$fld})";
          // $fieldsString[] = "{$fld} = _VALUES.{$fld}";
        }
        $qry .= " ON DUPLICATE KEY UPDATE " . implode("\n, ", $fieldsString);
        // $qry .= " AS _VALUES ON DUPLICATE KEY UPDATE " . implode("\n, ", $fieldsString);
      }

      $qry .= ';';

      $rowsCount = $this->queryExecute($qry, __FUNCTION__, __LINE__);

      //tbl_convert
      $qry =<<<SQL
      INSERT INTO tbl_convert(tableName, lastID, at)
           VALUES ('{$convertKey}', $lastID, NOW())
               ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
      $this->queryExecute($qry, __FUNCTION__, __LINE__);

      //commit
      $transaction->commit();
      return $rowsCount;

    } catch (\Throwable $exp) {
      $transaction->rollBack();
      throw $exp;
    }
  }

  public $jalali = null;
  public function jalaliToMiladi($value, $def = 'NULL', $qouted = true)
  {
    if ($this->jalali == null)
      $this->jalali = new Jalali();

    if ($value == null)
      return $def;

    $value = trim($value);
    if (empty($value) || str_starts_with($value, '-') || str_starts_with($value, '0000'))
      return $def;

    if (strpos($value, '/') === false) {
      if (strpos($value, '.') !== false)
        $value = str_replace('.', '/', $value);
      else
        return $def;
    }

    //meridiem
    $meridiemMap = [
      'ق.ظ' => 'am',
      'قبل از ظهر' => 'am',
      'ب.ظ' => 'pm',
      'بعد از ظهر' => 'pm',
    ];

    foreach ($meridiemMap as $km => $vm) {
      if (strpos($value, $km) !== false) {
        $value = str_replace(':' . $km,       '', $value);
        $value = str_replace(      $km . ':', '', $value);
        $value = str_replace(      $km,       '', $value);
        $value = $value . ' ' . $vm;
      }
    }

    $value = StringHelper::fixPersianCharacters($value);

    //---------------
    $value = str_replace(' : ', ' - ', $value);

    try {
      $hyphenIndex = strpos($value, '-');
      if ($hyphenIndex !== false) {
        $timePart = trim(substr($value, $hyphenIndex + 1));

        //error
        if (strpos($timePart, '-') !== false)
          return $def;

        $value = trim(substr($value, 0, $hyphenIndex - 1));
      }

      $parts = explode('/', $value);
      if (count($parts) != 3)
        return $def;

      if (strlen($parts[0]) == 3)
        $value = '1' . $value;
      else if (strlen($parts[0]) == 2)
        $value = '13' . $value;

      if (strlen($parts[0]) != 4)
        return $def;

      if ($parts[1] < 1 || $parts[1] > 12)
        return $def;

      if ($parts[2] < 1 || $parts[2] > 31)
        return $def;

      $ret = $this->jalali->setJalaliDate($value, '/')->getGregorian()->format('Y/m/d');

      if (isset($timePart)) {
        $timeParts = explode(':', $timePart);
        while (count($timeParts) < 3)
          $timeParts[] = '0';
        $timePart = implode(':', $timeParts);

        $ret .= ' - ' . $timePart;
      }

      if ($qouted == false)
        return $ret;

      return "'" . $ret . "'";

    } catch (\Throwable $exp) {
      echo "Error. date: " . $value;
      throw $exp;
    }
  }

  public function quotedString($value)
  {
    if ($value == null)
      return 'NULL';

    $value = trim($value);
    if (empty($value))
      return 'NULL';

    $value = str_replace("'", "\"", $value);
    return "'" . StringHelper::fixPersianCharacters($value) . "'";
  }

  public function nullIfEmpty($value, $nullValue = 'NULL')
  {
    if ($value == null)
      return $nullValue;

    $value = trim($value);
    if (empty($value))
      return $nullValue;

    return $value;
  }

  public function coalesce(array $values)
  {
    foreach ($values as $value) {
      if ((empty($value) == false)
        && (empty(trim($value)) == false)
      )
        return trim($value);
    }

    return null;
  }

  public function convert_categories_to_State(&$convertTableData)
  {
    $this->log("categories to state:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_categories[type=1][parent=0]';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

		$qry =<<<SQL
    SELECT tbl_categories.*
      FROM tbl_categories
     WHERE tbl_categories.tbl_categories_type = 1
       AND tbl_categories.tbl_categories_id > {$lastID}
       AND (
           tbl_categories.tbl_categories_parentid IS NULL
        OR tbl_categories.tbl_categories_parentid = '0'
           )
  ORDER BY tbl_categories.tbl_categories_id
SQL;

    $this->log("  fetching data from ({$lastID})+1...");
    $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

    if (empty($rows)) {
      $this->log("  nothing to do");
      return;
    }

    $this->log("  source data fetched");

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_AAA_GeoState', [
        'sttID',
        'sttUUID',
        'sttName',
        'sttCountryID',
      ], $values, $lastID, $convertKey);
    };

    // $lastID = 0;
    $values = [];
    foreach ($rows as $row) {
      $lastID = trim($row['tbl_categories_id']);

      $title = trim($row['tbl_categories_title']);
      if (empty($title))
        continue;

      $values[$lastID] = implode(',', [
        trim($row['tbl_categories_id']),
        "'" . trim($row['tbl_categories_code']) . "'",
        "'" . StringHelper::fixPersianCharacters($title) . "'",
        1, //ایران
      ]);

      if (count($values) >= 100) {
        $fnPutData($values, $lastID);
        $values = [];
      }
    }

    if (empty($values) == false) {
      $fnPutData($values, $lastID);
      $values = [];
    }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_categories_to_City(&$convertTableData)
  {
    $this->log("categories to city:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_categories[type=1][parent!=0]';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

		$qry =<<<SQL
    SELECT tbl_categories.*
         , parent.tbl_categories_id AS parentID
      FROM tbl_categories
 LEFT JOIN tbl_categories parent
        ON parent.tbl_categories_code = tbl_categories.tbl_categories_parentid
     WHERE tbl_categories.tbl_categories_type = 1
       AND tbl_categories.tbl_categories_id > {$lastID}
       AND tbl_categories.tbl_categories_parentid IS NOT NULL
       AND tbl_categories.tbl_categories_parentid != '0'
       AND TRIM(tbl_categories.tbl_categories_parentid) != ''
       AND tbl_categories.tbl_categories_code != tbl_categories.tbl_categories_parentid
  ORDER BY tbl_categories.tbl_categories_id
SQL;

    $this->log("  fetching data from ({$lastID})+1...");
    $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

    if (empty($rows)) {
      $this->log("  nothing to do");
      return;
    }

    $this->log("  source data fetched");

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_AAA_GeoCityOrVillage', [
        'ctvID',
        'ctvUUID',
        'ctvName',
        'ctvStateID',
      ], $values, $lastID, $convertKey);
    };

    // $lastID = 0;
    $values = [];
    foreach ($rows as $row) {
      $lastID = trim($row['tbl_categories_id']);

      $title = trim($row['tbl_categories_title']);
      if (empty($title))
        continue;

      $values[$lastID] = implode(',', [
        trim($row['tbl_categories_id']),
        "'" . trim($row['tbl_categories_code']) . "'",
        "'" . StringHelper::fixPersianCharacters($title) . "'",
        trim($row['parentID'])
      ]);

      if (count($values) >= 100) {
        $fnPutData($values, $lastID);
        $values = [];
      }
    }

    if (empty($values) == false) {
      $fnPutData($values, $lastID);
      $values = [];
    }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_categories_to_MhaBDef(&$convertTableData, $typeID, $typeName, $typeEnum)
  {
    $this->log("categories to {$typeName}");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = "v2.tbl_categories[type={$typeID}]";
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

		$qry =<<<SQL
    SELECT tbl_categories.*
      FROM tbl_categories
     WHERE tbl_categories.tbl_categories_type = {$typeID}
       AND tbl_categories.tbl_categories_id > {$lastID}
  ORDER BY tbl_categories.tbl_categories_id
SQL;

    $this->log("  fetching data from ({$lastID})+1...");
    $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

    if (empty($rows)) {
      $this->log("  nothing to do");
      return;
    }

    $this->log("  source data fetched");

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_MHA_BasicDefinition', [
        'bdfID',
        'bdfUUID',
        'bdfType',
        'bdfName',
      ], $values, $lastID, $convertKey);
    };

    // $lastID = 0;
    $values = [];
    foreach ($rows as $row) {
      $lastID = trim($row['tbl_categories_id']);

      $title = trim($row['tbl_categories_title']);
      if (empty($title))
        continue;

      $values[$lastID] = implode(',', [
        trim($row['tbl_categories_id']),
        "'" . trim($row['tbl_categories_code']) . "'",
        "'{$typeEnum}'",
        "'" . StringHelper::fixPersianCharacters($title) . "'"
      ]);

      if (count($values) >= 100) {
        $fnPutData($values, $lastID);
        $values = [];
      }
    }

    if (empty($values) == false) {
      $fnPutData($values, $lastID);
      $values = [];
    }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_categories_to_Document(&$convertTableData)
  {
    $this->log("categories to document:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_categories[type=10]';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

		$qry =<<<SQL
    SELECT tbl_categories.*
      FROM tbl_categories
     WHERE tbl_categories.tbl_categories_type = 10
       AND tbl_categories.tbl_categories_id > {$lastID}
  ORDER BY tbl_categories.tbl_categories_id
SQL;

    $this->log("  fetching data from ({$lastID})+1...");
    $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

    if (empty($rows)) {
      $this->log("  nothing to do");
      return;
    }

    $this->log("  source data fetched");

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_MHA_Document', [
        'docID',
        'docUUID',
        'docName',
        'docType',
      ], $values, $lastID, $convertKey);
    };

    // $lastID = 0;
    $values = [];
    foreach ($rows as $row) {
      $lastID = trim($row['tbl_categories_id']);

      $title = trim($row['tbl_categories_title']);
      if (empty($title))
        continue;

      $values[$lastID] = implode(',', [
        trim($row['tbl_categories_id']),
        "'" . trim($row['tbl_categories_code']) . "'",
        "'" . StringHelper::fixPersianCharacters($title) . "'",
        "'" . enuDocumentType::Other . "'"
      ]);

      if (count($values) >= 100) {
        $fnPutData($values, $lastID);
        $values = [];
      }
    }

    if (empty($values) == false) {
      $fnPutData($values, $lastID);
      $values = [];
    }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_club_to_Kanoon(&$convertTableData)
  {
    $this->log("club to kanoon:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_club';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

		$qry =<<<SQL
    SELECT tbl_club.*
      FROM tbl_club
     WHERE tbl_club.tbl_club_id > {$lastID}
  ORDER BY tbl_club.tbl_club_id
SQL;

    $this->log("  fetching data from ({$lastID})+1...");
    $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

    if (empty($rows)) {
      $this->log("  nothing to do");
      return;
    }

    $this->log("  source data fetched");

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_MHA_Kanoon', [
        'knnID',
        'knnUUID',
        'knnName',
        'knnNameEn',
      ], $values, $lastID, $convertKey);
    };

    // $lastID = 0;
    $values = [];
    foreach ($rows as $row) {
      $lastID = trim($row['tbl_club_id']);

      $title = StringHelper::fixPersianCharacters(trim($row['tbl_club_title']));
      if (empty($title))
        continue;

      $values[$lastID] = implode(',', [
        $lastID,
        "UUID()",
        "'{$title}'",
        $this->quotedString($row['tbl_club_titleen'])
      ]);

      if (count($values) >= 100) {
        $fnPutData($values, $lastID);
        $values = [];
      }
    }

    if (empty($values) == false) {
      $fnPutData($values, $lastID);
      $values = [];
    }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_profile_to_User(&$convertTableData)
  {
    $this->log("profile to User:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->user';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 2; //start from 3

		$qry =<<<SQL
    SELECT tbl_categories.tbl_categories_id AS city_id
         , TRIM(tbl_categories.tbl_categories_title) AS city_title
         , parent.tbl_categories_id AS state_id
         , TRIM(parent.tbl_categories_title) AS state_title
      FROM tbl_categories
 LEFT JOIN tbl_categories parent
        ON parent.tbl_categories_code = tbl_categories.tbl_categories_parentid
     WHERE tbl_categories.tbl_categories_type = 1
       AND tbl_categories.tbl_categories_parentid IS NOT NULL
       AND tbl_categories.tbl_categories_parentid != '0'
       AND TRIM(tbl_categories.tbl_categories_parentid) != ''
       AND tbl_categories.tbl_categories_code != tbl_categories.tbl_categories_parentid
  ORDER BY tbl_categories.tbl_categories_id
SQL;
    $CityAndStatesRows = $oldcrmdbv2->createCommand($qry)->queryAll();

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_AAA_User', [
        'usrID',
        'usrUUID',
        'usrGender',
        'usrFirstName',
        'usrFirstName_en',
        'usrLastName',
        'usrLastName_en',
        'usrFatherName',
        'usrFatherName_en',
        'usrEmail',
        // 'usrEmailApprovedAt',
        'usrMobile',
        // 'usrMobileApprovedAt',
        'usrSSID',
        'usrBirthCertID',
        'usrRoleID',
        'usrPrivs',
        // 'usrPasswordHash',
        // 'usrPasswordCreatedAt',
        'usrBirthDate',
        'usrBirthCityID',
        'usrCountryID',
        'usrStateID',
        'usrCityOrVillageID',
        // 'usrTownID',
        'usrHomeAddress',
        'usrZipCode',
        'usrPhones',
        'usrWorkAddress',
        'usrWorkPhones',
        'usrWebsite',
        // 'usrImageFileID',
        'usrCreatedAt',
        'usrUpdatedAt',
      ], $values, $lastID, $convertKey);
    };

    $values = [];
    $values_cache_emaile = [];
    $values_cache_mobile = [];
    $values_cache_ssid = [];

    $fetchCount = 1000;
    $saveCount = 100;
    $loopCount = 0;
    while (true) {
      ++$loopCount;

      $qry =<<<SQL
      SELECT tbl_profile.*
           , tbl_address.*
           , tbl_otherinfo.*
           , state.tbl_categories_id AS stateID
           , city.tbl_categories_id AS cityID
           , birth_state.tbl_categories_id AS birthStateID

        FROM tbl_profile

   LEFT JOIN tbl_address
          ON tbl_address.tbl_address_systemcode = tbl_profile.tbl_profile_systemcode

   LEFT JOIN tbl_otherinfo
          ON tbl_otherinfo.tbl_otherinfo_systemcode = tbl_profile.tbl_profile_systemcode

   LEFT JOIN tbl_categories state
          ON TRIM(state.tbl_categories_title) = TRIM(tbl_address.tbl_address_fld1)
         AND state.tbl_categories_type = 1
         AND IFNULL(state.tbl_categories_parentid, '0') = '0'
         AND TRIM(tbl_address.tbl_address_fld1) != ''

   LEFT JOIN tbl_categories city
          ON TRIM(city.tbl_categories_title) = TRIM(tbl_address.tbl_address_fld2)
         AND city.tbl_categories_type = 1
         AND IFNULL(city.tbl_categories_parentid, '0') = state.tbl_categories_code
         AND TRIM(tbl_address.tbl_address_fld2) != ''

   LEFT JOIN tbl_categories birth_state
          ON TRIM(birth_state.tbl_categories_title) = TRIM(tbl_profile.tbl_profile_fldn7)
         AND birth_state.tbl_categories_type = 1
         AND IFNULL(birth_state.tbl_categories_parentid, '0') = '0'
         AND TRIM(tbl_profile.tbl_profile_fldn7) != ''

       WHERE tbl_profile.tbl_profile_id > {$lastID}
    ORDER BY tbl_profile.tbl_profile_id

       LIMIT {$fetchCount}
SQL;

      $this->log("  fetching data from ({$lastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_profile_id']);

        //------------
        $gender = 'NULL';
        if (empty($row['tbl_profile_fld16']) == false) {
          if (trim($row['tbl_profile_fld16']) == 'مرد')
            $gender = "'" . enuGender::Male . "'";
          else if (trim($row['tbl_profile_fld16']) == 'زن')
            $gender = "'" . enuGender::Female . "'";
        }

        //------------
        $email = '';

        if (empty($row['tbl_address_fld16']) == false && empty(trim($row['tbl_address_fld16'])) == false)
          $email = trim($row['tbl_address_fld16']);

        if (empty($email) && (empty($row['tbl_profile_email']) == false) && (empty(trim($row['tbl_profile_email'])) == false))
          $email = trim($row['tbl_profile_email']);

        $email = strtolower($email);

        if ($email == 'info@iranhmusic.ir')
          $email = '';

        if (empty($email == false)) {
          $duplicate = false;
          if (isset($values_cache_email[$email])) {
            $duplicate = true;
          } else {
            $qry = "SELECT COUNT(*) AS cnt FROM tbl_AAA_User WHERE usrEmail = '{$email}'";
            $eee = $this->queryOne($qry, __FUNCTION__, __LINE__);
            if (empty($eee) == false && ($eee['cnt'] ?? 0 > 0)) {
              $duplicate = true;
            }
          }

          if ($duplicate) {
            $parts = explode('@', $email);
            $email = $parts[0];
            unset($parts[0]);
            $email .= "+duplicate_{$lastID}" . '@' . implode('@', $parts);
            echo "  duplicate email. changed to {$email}\n";
          }
        }

        if (empty($email) == false)
          $values_cache_email[$email] = 1;

        $email = $this->quotedString($email);

        //-------------------------------------------------
        $phones = [];

        //------------
        $mobile = '';
        if (empty($row['tbl_address_fld9']) == false)
          $mobile = trim($row['tbl_address_fld9']);

        if (($mobile == 'ندارند')
          || ($mobile == 'ندارد')
          || ($mobile == '0')
          || ($mobile == '00')
          || ($mobile == '00000000000')
        )
          $mobile = '';
        else
          $phones[] = $mobile;

        if (empty($mobile) == false) {
          $mobile = preg_replace('/[^0-9]/', '', $mobile);
        }

        if (str_starts_with($mobile, '00') == false) {
          if (strlen($mobile) > 11)
            $mobile = substr($mobile, 0, 11);
            // $mobile = substr($mobile, -11);

          if (strlen($mobile) == 10 && str_starts_with($mobile, '9'))
            $mobile = '0' . $mobile;

          if (strlen($mobile) == 11 && str_starts_with($mobile, '19'))
            $mobile = '0' . substr($mobile, 1);

          if (strlen($mobile) != 11)
            $mobile = '';
        }

        if (empty($mobile == false)) {
          $eee = PhoneHelper::normalizePhoneNumber($mobile);
          if ($eee == false)
            $mobile = '';
          else {
            $mobile = $eee;

            $duplicate = false;
            if (isset($values_cache_mobile[$mobile])) {
              $duplicate = true;
            } else {
              $qry = "SELECT COUNT(*) AS cnt FROM tbl_AAA_User WHERE usrMobile = '{$mobile}'";
              $eee = $this->queryOne($qry, __FUNCTION__, __LINE__);
              if (empty($eee) == false && ($eee['cnt'] ?? 0 > 0)) {
                $duplicate = true;
              }
            }

            if ($duplicate) {
              echo "  duplicate mobile {$mobile}.\n";
              $mobile = '';
            }
          }
        }

        if (empty($mobile) == false)
          $values_cache_mobile[$mobile] = 1;

        $mobile = $this->quotedString($mobile);

        //------------
        $phone = '';
        if (empty($row['tbl_address_fld10']) == false) {
          $phone = trim($row['tbl_address_fld10']);
          $phones[] = $phone;
        }

        //------------
        $workphone = '';
        if (empty($row['tbl_address_fld13']) == false) {
          $workphone = trim($row['tbl_address_fld13']);
          $phones[] = $workphone;
        }

        //------------
        $emergencyphone = '';
        if (empty($row['tbl_address_fld14']) == false) {
          $emergencyphone = trim($row['tbl_address_fld14']);
          $phones[] = $emergencyphone;
        }

        //------------
        $phones = array_filter($phones);
        $phones = $this->quotedString(implode(',', $phones));

        //-------------------------------------------------
        $homeAddress = '';

        if (empty($row['stateID']) && empty($row['tbl_address_fld1']) == false) {
          $homeAddress = trim($row['tbl_address_fld1']);
        } else {
          $homeAddress = [];

          //خ اصلی
          if (empty($row['tbl_address_fld3']) == false)
            $homeAddress[] = trim($row['tbl_address_fld3']);

          //خ فرعی
          if (empty($row['tbl_address_fld4']) == false)
            $homeAddress[] = trim($row['tbl_address_fld4']);

          //کوچه
          if (empty($row['tbl_address_fld5']) == false)
            $homeAddress[] = 'کوچه ' . trim($row['tbl_address_fld5']);

          //پلاک
          if (empty($row['tbl_address_fld6']) == false)
            $homeAddress[] = 'پلاک ' . trim($row['tbl_address_fld6']);

          //طبقه
          if (empty($row['tbl_address_fld7']) == false)
            $homeAddress[] = 'طبقه ' . trim($row['tbl_address_fld7']);

          //واحد
          if (empty($row['tbl_address_fld8']) == false)
            $homeAddress[] = 'واحد ' . trim($row['tbl_address_fld8']);

          $homeAddress = implode(' - ', $homeAddress);
        }

        $homeAddress = $this->quotedString($homeAddress);

        //------------
        $uuid = trim($row['tbl_profile_systemcode']);

        //------------
        $ssid = trim($row['tbl_profile_fld5']);
        if (($ssid == '0000000000') || ($ssid == '1111111111'))
          $ssid = '';

        if (empty($ssid) == false) {
          $duplicate = false;
          if (isset($values_cache_ssid[$ssid])) {
            $duplicate = true;
          } else {
            $qry = "SELECT COUNT(*) AS cnt FROM tbl_AAA_User WHERE usrSSID = '{$ssid}'";
            $eee = $this->queryOne($qry, __FUNCTION__, __LINE__);
            if (empty($eee) == false && ($eee['cnt'] ?? 0 > 0)) {
              $duplicate = true;
            }
          }

          if ($duplicate) {
            echo "  duplicate ssid {$ssid}.\n";
            $ssid = '';
          }
        }

        if (empty($ssid) == false)
          $values_cache_ssid[$ssid] = 1;

        $ssid = $this->quotedString($ssid);

        //------------
        $birthCityID = 'NULL';
        $birthCityName = trim($row['tbl_profile_fld7']);
        if (empty($birthCityName) == false) {
          $birthStateID = $row['birthStateID'];

          $founds = [];
          foreach ($CityAndStatesRows as $aaa) {
            // $aaa['city_id']
            // $aaa['city_title']
            // $aaa['state_id']
            // $aaa['state_title']

            if ($birthCityName == trim($aaa['city_title'])) {
              if (empty($birthStateID) || ($birthStateID == $aaa['state_id'])) {
                $founds[] = $aaa;

                if (empty($birthStateID) == false)
                  break;
              }
            }
          }

          if (empty($founds) == false) {
            $birthCityID = $founds[0]['city_id'];
          }
        }

        //------------
        $usrBirthDate = $this->jalaliToMiladi($row['tbl_profile_fld3']);
        if (($usrBirthDate == 'NULL') && (empty($row['tbl_otherinfo_fld4']) == false)) {
          $usrBirthDate = $this->quotedString($row['tbl_otherinfo_fld4']);
        }

        //------------
        try {
          $values[$lastID] = implode(',', [
            /* usrID                */ $lastID + 100,
            /* usrUUID              */ $this->quotedString($uuid),
            /* usrGender            */ $gender,
            /* usrFirstName         */ $this->quotedString($row['tbl_profile_fld1']),
            /* usrFirstName_en      */ $this->quotedString($this->coalesce([$row['tbl_otherinfo_fld1'], $row['tbl_profile_fld17']])),
            /* usrLastName          */ $this->quotedString($row['tbl_profile_fld2']),
            /* usrLastName_en       */ $this->quotedString($this->coalesce([$row['tbl_otherinfo_fld2'], $row['tbl_profile_fld18']])),
            /* usrFatherName        */ $this->quotedString($row['tbl_profile_fld6']),
            /* usrFatherName_en     */ $this->quotedString($row['tbl_otherinfo_fld3']),
            /* usrEmail             */ $email,
            /* usrEmailApprovedAt   */
            /* usrMobile            */ $mobile,
            /* usrMobileApprovedAt  */
            /* usrSSID              */ $ssid,
            /* usrBirthCertID       */ $this->quotedString($row['tbl_profile_fld4']),
            /* usrRoleID            */ 10,
            /* usrPrivs             */ 'NULL',
            /* usrPasswordHash      */
            /* usrPasswordCreatedAt */
            /* usrBirthDate         */ $usrBirthDate,
            /* usrBirthCityID       */ $birthCityID,
            /* usrCountryID         */ 1,
            /* usrStateID           */ $this->nullIfEmpty($row['stateID']), //from tbl_address
            /* usrCityOrVillageID   */ $this->nullIfEmpty($row['cityID']), //from tbl_address
            /* usrTownID            */
            /* usrHomeAddress       */ $homeAddress,
            /* usrZipCode           */ $this->quotedString($row['tbl_address_fld11']), //from tbl_address
            /* usrPhones            */ $phones,
            /* usrWorkAddress       */ $this->quotedString($row['tbl_address_fld12']),
            /* usrWorkPhones        */ $this->quotedString($workphone),
            /* usrWebsite           */ $this->quotedString($row['tbl_address_fld15']),
            /* usrImageFileID       */
            /* usrCreatedAt         */ $this->jalaliToMiladi($row['tbl_profile_date'], 'NOW()'),
            /* usrUpdatedAt         */ $this->jalaliToMiladi($row['tbl_profile_editdate']),
          ]);
        } catch (\Throwable $exp) {
          echo "** ERROR: ID: {$lastID} **\n";
          throw $exp;
        }

        if (count($values) >= $saveCount) {
          $fnPutData($values, $lastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $lastID);
        $values = [];
      }

    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_profile_to_Member(&$convertTableData)
  {
    $this->log("profile to Member:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->member';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 2; //start from 3

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_MHA_Member', [
        'mbrUserID',
        'mbrUUID',
        'mbrRegisterCode',
        // 'mbrAcceptedAt',
        'mbrMusicExperiences',
        // 'mbrMusicExperienceStartAt',
        'mbrArtHistory',
        'mbrMusicEducationHistory',
        'mbrOwnOrgName',
        // 'mbrStatus',
        'mbrCreatedAt',
        'mbrUpdatedAt',
      ], $values, $lastID, $convertKey);
    };

    $values = [];

    $fetchCount = 1000;
    $saveCount = 100;
    $loopCount = 0;
    while (true) {
      ++$loopCount;

      $qry =<<<SQL
      SELECT tbl_profile.*
           , tbl_address.*
           , tbl_otherinfo.*

        FROM tbl_profile

   LEFT JOIN tbl_address
          ON tbl_address.tbl_address_systemcode = tbl_profile.tbl_profile_systemcode

   LEFT JOIN tbl_otherinfo
          ON tbl_otherinfo.tbl_otherinfo_systemcode = tbl_profile.tbl_profile_systemcode

       WHERE tbl_profile.tbl_profile_id > {$lastID}
         AND tbl_profile.tbl_profile_id != 4
    ORDER BY tbl_profile.tbl_profile_id

       LIMIT {$fetchCount}
SQL;

      $this->log("  fetching data from ({$lastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_profile_id']);

        //------------
        try {
          $mbrMusicExperiences = [];
          if (empty($row['tbl_profile_fld8'])  == false) $mbrMusicExperiences[] = trim($row['tbl_profile_fld8']);
          if (empty($row['tbl_profile_fld9'])  == false) $mbrMusicExperiences[] = trim($row['tbl_profile_fld9']);
          if (empty($row['tbl_profile_fld10']) == false) $mbrMusicExperiences[] = trim($row['tbl_profile_fld10']);
          $mbrMusicExperiences = implode(' - ', $mbrMusicExperiences);

          $values[$lastID] = implode(',', [
            /* mbrUserID                 */ $lastID + 100,
            /* mbrUUID                   */ 'UUID()',
            /* mbrRegisterCode           */ $this->nullIfEmpty($row['tbl_profile_code']),
            /* mbrAcceptedAt             */
            /* mbrMusicExperiences       */ $this->quotedString($mbrMusicExperiences),
            /* mbrMusicExperienceStartAt */
            /* mbrArtHistory             */ $this->quotedString($row['tbl_otherinfo_fld11']),
            /* mbrMusicEducationHistory  */ $this->quotedString($row['tbl_profile_fld15']),
            /* mbrOwnOrgName             */ $this->quotedString($row['tbl_address_fldn27']),
            /* mbrStatus                 */
            /* mbrCreatedAt              */ $this->jalaliToMiladi($row['tbl_profile_date'], 'NOW()'),
            /* mbrUpdatedAt              */ $this->jalaliToMiladi($row['tbl_profile_editdate']),
          ]);
        } catch (\Throwable $exp) {
          echo "** ERROR: ID: {$lastID} **\n";
          throw $exp;
        }

        if (count($values) >= $saveCount) {
          $fnPutData($values, $lastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $lastID);
        $values = [];
      }

    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  /**
   * return ($queryLastID, $errorids)
   */
  public function initializeWorker(
    &$convertTableData,
    $convertKey
  ) {
    $queryLastID = $convertTableData[$convertKey]['lastID'] ?? 2; //start from 3

    //-----------------
    $errorids = $convertTableData[$convertKey]['info'] ?? null;
    if (empty($errorids))
      $errorids = [];
    else
      $errorids = explode(',', $errorids);

    if (empty($errorids) == false) {
      $errorids = array_combine(array_values($errorids), array_values($errorids));
      $this->log("  last errorids: " . implode(',', $errorids));
    }

    return [$queryLastID, $errorids];
  }

  public function fnRemoveFromErrorIDs($lastID, &$errorids, &$processedErrorIds)
  {
    if (empty($errorids[$lastID]))
      return false;

    unset($errorids[$lastID]);
    $processedErrorIds[$lastID] = $lastID;

    return true;
  }

  public function fnLogErrorToConvertTable($lastID, $err, $convertKey, &$errorids, &$processedErrorIds)
  {
    $this->log("  ERROR ON '{$lastID}' {$err}");

    $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at, info)
       VALUES ('{$convertKey}', 0, NOW(), '{$lastID}')
           ON DUPLICATE KEY UPDATE
              info = IF (info IS NULL OR LENGTH(info) = 0,
                '{$lastID}',
                IF (LOCATE(',{$lastID},', CONCAT(',', info, ',')) >= 1,
                  info,
                  CONCAT(info, ',', '{$lastID}')
                )
              )
            , at = NOW()
            ;
SQL;
    $this->queryExecute($qry, __FUNCTION__, __LINE__);

    //prevent fetch in next loops
    $this->fnRemoveFromErrorIDs($lastID, $errorids, $processedErrorIds);
  }

  public function fnUnLogErrorFromConvertTable(?array $ids, $convertKey, &$errorids, &$processedErrorIds)
  {
    //remove lastID from tbl_convert.info

    $idsForRemove = array_filter($ids, function($var) use($errorids) {
      return isset($errorids[$var]);
    });

    if (empty($idsForRemove))
      return;

    $this->log("  REMOVE '" . implode(',', $idsForRemove) . "' FROM tbl_convert ERRORS");

    $replaces = '';
    foreach ($idsForRemove as $k => $id) {
      $this->fnRemoveFromErrorIDs($id, $errorids, $processedErrorIds);

      if ($k == 0) {
        $replaces = "REPLACE(CONCAT(',', info, ',') , ',{$id},', ',')";
      } else {
        $replaces =
            "REPLACE("
          . $replaces
          . ", ',{$id},', ',')";
      }
    }

    $qry =<<<SQL
  UPDATE tbl_convert
     SET info = IF (info IS NULL OR LENGTH(info) = 0,
           NULL, TRIM(BOTH ',' FROM {$replaces})
         )
       , at = NOW()
   WHERE tableName = '{$convertKey}';
SQL;
// var_dump(['$qry' => $qry]);
    $this->queryExecute($qry, __FUNCTION__, __LINE__);
  }

  public function convert_profile_to_Mbr_Kanoon(&$convertTableData)
  {
    $this->log("profile to Member-Kanoon:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->member-kanoon';

    list ($queryLastID, $errorids) = $this->initializeWorker($convertTableData, $convertKey);

    $processedErrorIds = [];

    //-------------------------
    $qry =<<<SQL
    SELECT *
      FROM tbl_club
SQL;

    $rows = $oldcrmdbv2->createCommand($qry)->queryAll();
    if (empty($rows)) {
      $this->log("  source clubs is empty");
      return;
    }

    $clubIDs = [];

    //phase 1
    foreach ($rows as $row) {
      $tbl_club_title = trim($row['tbl_club_title']);
      if (strpos($tbl_club_title, '-') === false) {
        $clubIDs[$tbl_club_title] = [$row['tbl_club_id']];
      }
    }

    //phase 2: multi clubs
    foreach ($rows as $row) {
      $tbl_club_title = trim($row['tbl_club_title']);
      if (strpos($tbl_club_title, '-') !== false) {
        $parts = explode('-', $tbl_club_title);

        $ids = [];
        foreach ($parts as $part) {
          $ids[] = $clubIDs[trim($part)][0];
        }

        $clubIDs[$tbl_club_title] = $ids;
      }
    }

    //-------------------------
    $clubDegrees = [
      "پیوسته"           => [enuKanoonMembershipDegree::Continuous],
      "وابسته1"          => [enuKanoonMembershipDegree::Dependent1],
      "وابسته2"          => [enuKanoonMembershipDegree::Dependent2],
      "وابستهدو"         => [enuKanoonMembershipDegree::Dependent2],
      "پیوسته-پیوسته"    => [enuKanoonMembershipDegree::Continuous, enuKanoonMembershipDegree::Continuous],
      "پیوسته-وابسته1"   => [enuKanoonMembershipDegree::Continuous, enuKanoonMembershipDegree::Dependent1],
      "پیوسته-وابسته2"   => [enuKanoonMembershipDegree::Continuous, enuKanoonMembershipDegree::Dependent2],
      "وابسته1-پیوسته"   => [enuKanoonMembershipDegree::Dependent1, enuKanoonMembershipDegree::Continuous],
      "وابسته1-وابسته1"  => [enuKanoonMembershipDegree::Dependent1, enuKanoonMembershipDegree::Dependent1],
      "وابسته1-وابسته2"  => [enuKanoonMembershipDegree::Dependent1, enuKanoonMembershipDegree::Dependent2],
      "وابسته2-پیوسته"   => [enuKanoonMembershipDegree::Dependent2, enuKanoonMembershipDegree::Continuous],
      "وابسته2-وابسته1"  => [enuKanoonMembershipDegree::Dependent2, enuKanoonMembershipDegree::Dependent1],
      "وابسته2-وابسته2"  => [enuKanoonMembershipDegree::Dependent2, enuKanoonMembershipDegree::Dependent2],
      "کد۲۵"              => [enuKanoonMembershipDegree::Code25],
    ];

    //-------------------------
    $fnPutData = function(array $values, $lastID) use($convertKey, &$errorids, &$processedErrorIds) {
      $this->putData('tbl_MHA_Member_Kanoon', [
        'mbrknnUUID',
        'mbrknnMemberID',
        'mbrknnKanoonID',
        // 'mbrknnParams',
        'mbrknnIsMaster',
        'mbrknnMembershipDegree',
        'mbrknnComment',
        'mbrknnHistory',
        'mbrknnStatus',
        // 'mbrknnCreatedAt',
      ], $values, $lastID, $convertKey, [
        'mbrknnIsMaster', // for ignore duplicate error
      ]);

      $this->fnUnLogErrorFromConvertTable(array_keys($values), $convertKey, $errorids, $processedErrorIds);
    };

    $values = [];
    $fetchCount = 1000;
    $saveCount = 100;
    $loopCount = 0;

    while (true) {
      ++$loopCount;

      // if ($loopCount > 1)
      //   break;

      //-- create where and newFetchCount -------------------------------
      $thisLoopErrorIDs = array_filter($errorids, function($var) use($queryLastID) {
        return ($var <= $queryLastID);
      });

      $erroridsCount = count($thisLoopErrorIDs);
      $newFetchCount = $fetchCount;
      if ($erroridsCount > $newFetchCount)
        $newFetchCount += $erroridsCount;

      $where = "(tbl_profile.tbl_profile_id > {$queryLastID} AND tbl_profile.tbl_profile_id != 4)";
      if (empty($thisLoopErrorIDs) == false) {
        $where = '(' . $where . "\nOR tbl_profile.tbl_profile_id IN (" . implode(',', $thisLoopErrorIDs) . ")\n)";
      }
      $where .= "\n";
      if (empty($processedErrorIds) == false) {
        $where .= "AND tbl_profile.tbl_profile_id NOT IN (" . implode(',', $processedErrorIds) . ")\n";
      }

      // var_dump(['thisLoopErrorIDs' => $thisLoopErrorIDs, 'where' => $where]);

      //---------------------------------
      $qry =<<<SQL
      SELECT tbl_profile.*
           , tbl_club.*

        FROM tbl_profile

  INNER JOIN tbl_club
          ON tbl_club.tbl_club_id = tbl_profile.tbl_profile_club

       WHERE {$where}

    ORDER BY tbl_profile.tbl_profile_id

       LIMIT {$newFetchCount}
SQL;

      $this->log("  fetching data from ({$queryLastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_profile_id']);

        if ($lastID > $queryLastID)
          $queryLastID = $lastID;

        //------------
        try {
          $tbl_club_title = trim($row['tbl_club_title']);

          $ids = $clubIDs[$tbl_club_title];

          $tbl_profile_order = trim($row['tbl_profile_order']);
          if (empty($tbl_profile_order)) {
            $degrees = null;
          // } else if ($tbl_profile_order == trim($row['tbl_profile_code'])) { //error: code 8992
          //   $degrees = [enuKanoonMembershipDegree::Dependent1];
          } else {
            $tbl_profile_order = str_replace(' ', '', $tbl_profile_order);
            $degrees = $clubDegrees[$tbl_profile_order];
          }

          // if (count($ids) != count($degrees))
          //   throw new \Exception("club and order data does not match");

          $tbl_profile_op1     = $this->coalesce([$row['tbl_profile_op1']]);
          $tbl_profile_op2     = $this->coalesce([$row['tbl_profile_op2']]);
          $tbl_profile_op1date = $this->jalaliToMiladi($row['tbl_profile_op1date'], null, false);
          $tbl_profile_op2date = $this->jalaliToMiladi($row['tbl_profile_op2date'], null, false);

          $tbl_profile_expiredate  = $this->coalesce([$row['tbl_profile_expiredate']]);
          $tbl_profile_commission  = trim($row['tbl_profile_commission']); if ($tbl_profile_commission == '') $tbl_profile_commission = null;
          $tbl_profile_stepone     = trim($row['tbl_profile_stepone']);    if ($tbl_profile_stepone == '') $tbl_profile_stepone = null;
          // $tbl_profile_statuscheck = $this->coalesce([$row['tbl_profile_statuscheck']]);
          // $tbl_profile_steptwo     = $this->coalesce([$row['tbl_profile_steptwo']]);

          //from v1?
          if (($tbl_profile_expiredate != null) && ($tbl_profile_commission == '0')) {
            $newStatus = enuMemberKanoonStatus::Accepted;
          } else {
            switch ($tbl_profile_commission) {
              case 0: //منتظر نظر کمیسیون
                if ($tbl_profile_stepone == 0)
                  $newStatus = enuMemberKanoonStatus::WaitForSend;
                else
                  $newStatus = enuMemberKanoonStatus::WaitForSurvey;
                break;
              case 1: //تایید کمیسیون
                $newStatus = enuMemberKanoonStatus::Accepted;
                break;
              case 2: //آزمون
                $newStatus = enuMemberKanoonStatus::Azmoon;
                break;
              case 3: //آزمون مجدد
                $newStatus = enuMemberKanoonStatus::WaitForResurvey;
                break;
              case 4: //ارایه مدرک بیشتر
                $newStatus = enuMemberKanoonStatus::WaitForDocuments;
                break;
              case 5: //مردود
                $newStatus = enuMemberKanoonStatus::Rejected;
                break;
            }
          }

          $history = [];

          if ((empty($tbl_profile_op1) == false) || (empty($tbl_profile_op1date) == false)) {
            $historyItem = [
              'status' => $newStatus,
            ];

            if ((empty($tbl_profile_op1date) == false)) {
              $dt = new \DateTime($tbl_profile_op1date);
              $historyItem['at'] = $dt->format('U');
            }
            if ((empty($tbl_profile_op1) == false)) {
              $historyItem['comment'] = preg_replace("/\r\n|\n\r|\r|\n/", "\\n", $tbl_profile_op1);
            }

            $history[] = $historyItem;
          }

          if ((empty($tbl_profile_op2) == false) || (empty($tbl_profile_op2date) == false)) {
            $historyItem = [
              'status' => $newStatus,
            ];

            if ((empty($tbl_profile_op2date) == false)) {
              $dt = new \DateTime($tbl_profile_op2date);
              $historyItem['at'] = $dt->format('U');
            }
            if ((empty($tbl_profile_op2) == false)) {
              $historyItem['comment'] = preg_replace("/\r\n|\n\r|\r|\n/", "\\n", $tbl_profile_op2);
            }

            $history[] = $historyItem;
          }

          $lastcomment = $this->coalesce([$tbl_profile_op2, $tbl_profile_op1]);

/*          print_r([
            'profile' => $lastID,
            'club ids' => $ids,
            'club degrees' => $degrees,
            'tbl_profile_op1' => $tbl_profile_op1,
            'tbl_profile_op1date' => $tbl_profile_op1date,
            'tbl_profile_op2' => $tbl_profile_op2,
            'tbl_profile_op2date' => $tbl_profile_op2date,

            'tbl_profile_expiredate' => $tbl_profile_expiredate,
            'tbl_profile_commission' => $tbl_profile_commission,
            'tbl_profile_stepone' => $tbl_profile_stepone,

            'history' => $history,
            'lastcomment' => $lastcomment,
          ]);
/**/
          if (empty($history))
            $history = null;
          else
            $history = Json::encode($history);

          foreach ($ids as $clubidx => $clubid) {
            $values[$lastID + ($clubidx * 100000)] = implode(',', [
              /* mbrknnUUID             */ 'UUID()',
              /* mbrknnMemberID         */ $lastID + 100,
              /* mbrknnKanoonID         */ $clubid,
              // /* mbrknnParams           */ 'NULL', //$params,
              /* mbrknnIsMaster         */ $clubidx == 0 ? 1 : 0,
              /* mbrknnMembershipDegree */ $this->quotedString($degrees[$clubidx] ?? $degrees[0] ?? null),
              /* mbrknnComment          */ $this->quotedString($lastcomment),
              /* mbrknnHistory          */ $this->quotedString($history),
              /* mbrknnStatus           */ $this->quotedString($newStatus),
            //   /* mbrknnCreatedAt        */ $this->jalaliToMiladi($row['tbl_profile_date'], 'NOW()'),
            ]);
          }

        } catch (\Throwable $exp) {
          $this->fnLogErrorToConvertTable($lastID, $exp->getMessage(), $convertKey, $errorids, $processedErrorIds);
          // echo "** ERROR: ID: {$lastID} **\n";
          // throw $exp;
        }

        if (count($values) >= $saveCount) {
          $fnPutData($values, $queryLastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $queryLastID);
        $values = [];
      }
    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $queryLastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $queryLastID
      ];

    $this->log("  converted to '{$queryLastID}'");
  }

  public function convert_expert_to_Mbr_Specialty(&$convertTableData)
  {
    $this->log("expert to Mbr_Specialty:");

    //specialty types ----------------
		$qry =<<<SQL
    SELECT *
      FROM tbl_MHA_Specialty
  ORDER BY spcRoot
         , spcLeft
SQL;
    $rows = $this->queryAll($qry, __FUNCTION__, __LINE__);

    $specialties = [];
    foreach ($rows as $v) {
      if ($v['spcRoot'] == $v['spcID']) {
        $specialties[$v['spcID']] = $v;
      } else {
        $specialties[$v['spcRoot']]['items'] = array_merge($specialties[$v['spcRoot']]['items'] ?? [], [
          $v['spcID'] => $v
        ]);
      }
    }

    $newSpcs = [];
    if (empty($specialties[1]))  $newSpcs[1]  = ['name' => 'آهنگساز'];
    if (empty($specialties[2]))  $newSpcs[2]  = ['name' => 'تنظیم کننده'];
    if (empty($specialties[3]))  $newSpcs[3]  = ['name' => 'خواننده'];
    if (empty($specialties[4]))  $newSpcs[4]  = ['name' => 'نوازنده'];
    if (empty($specialties[5]))  $newSpcs[5]  = ['name' => 'رهبر ارکستر'];
    if (empty($specialties[6]))  $newSpcs[6]  = ['name' => 'رهبر کر'];
    if (empty($specialties[7]))  $newSpcs[7]  = ['name' => 'سازنده ساز'];
    // if (empty($specialties[8]))  $newSpcs[8]  = ['name' => 'نام ساز'];
    if (empty($specialties[9]))  $newSpcs[9]  = ['name' => 'مدرس ساز ایرانی', 'fieldType' => 'text']; //mha:I
    if (empty($specialties[10])) $newSpcs[10] = ['name' => 'مدرس ساز کلاسیک',  'fieldType' => 'text']; //mha:I
    if (empty($specialties[11])) $newSpcs[11] = ['name' => 'مدرس ساز پاپ',    'fieldType' => 'text']; //mha:I
    if (empty($specialties[12])) $newSpcs[12] = ['name' => 'سازهای دیگر'];
    if (empty($specialties[13])) $newSpcs[13] = ['name' => 'دروس نظری و تخصصی'];
    if (empty($specialties[14])) $newSpcs[14] = ['name' => 'آواز'];
    if (empty($specialties[15])) $newSpcs[15] = ['name' => 'غیره'];
    if (empty($specialties[16])) $newSpcs[16] = ['name' => 'پژوهشگر'];

    $values = [];
    foreach ($newSpcs as $k => $v) {
      $value = [
        'spcID'             => $k,
        'spcUUID'           => 'UUID()',
        'spcRoot'           => $k,
        'spcLeft'           => 1,
        'spcRight'          => 2,
        'spcLevel'          => 0,
        'spcName'           => $this->quotedString($v['name']),
        // 'spcDesc'           =>
        'spcDescFieldType'  => isset($v['fieldType']) ? "'" . $v['fieldType'] . "'" : 'NULL',
        // 'spcDescFieldLabel' =>
      ];

      $values[] = implode(',', $value);

      $value['spcName']          = str_replace("'", "", $value['spcName']);
      $value['spcDescFieldType'] = str_replace("'", "", $value['spcDescFieldType']);
      $specialties[$k] = $value;
    }

    if (empty($values) == false) {
      $qry =<<<SQL
        INSERT INTO tbl_MHA_Specialty(
          spcID,
          spcUUID,
          spcRoot,
          spcLeft,
          spcRight,
          spcLevel,
          spcName,
          spcDescFieldType
        ) VALUES
SQL;
      $qry .= '(' . implode('),(', $values) . ');';
      $rowsCount = $this->queryExecute($qry, __FUNCTION__, __LINE__);
    }

    //----------------
    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_expert';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

    $keywords = [
      1 => [
        'سنتی',
        'پاپ',
        'کودک',
        'کلاسیک',
        'ملی',
        'مذهبی',
        'محلی',
      ],
      2 => [
        'وله یا تیزر تلویزیونی',
        'سنتی',
        'پاپ',
        'کودک',
        'کلاسیک',
        'ملی',
        'مذهبی',
        'محلی',
        'فیلم',
        'تئاتر',
      ],
      3 => [
        'سنتی',
        'پاپ',
        'کودک',
        'کلاسیک',
        'ملی',
        'مذهبی',
        'محلی',
      ],
      4 => [
        'سنتی',
        'پاپ',
        'کودک',
        'کلاسیک',
        'ملی',
        'مذهبی',
        'محلی',
      ],
      5 => [
        'کودک و نوجوان',
        'پاپ',
        'سمفونیک',
        'ملی',
        'سنتی',
        'سبک',
      ],
      6 => [
        'کودک و نوجوان',
        'بزرگسالان',
      ],
      7 => [
        'زهی مضرابی',
        'زهی کمان',
        'کوبه ای',
        'بومی مناطق',
        'ایرانی',
        'سنتی',
        'بادی',
      ],
      12 => [
        'کیبورد و ملودی های صوتی',
        'بادی چوبی',
        'بادی برنجی',
        'کوبه ای',
        'زهی',
        'الکترونیک',
        'مضرابی',
      ],
      13 => [
        'موسیقی قدیم ایران',
        'تئوری موسیقی ایران',
        'تئوری موسیقی',
        'تاریخ موسیقی',
        'کنتر پوان',
        'فرم و آنلایز',
        'ساز شناسی',
        'اصول آهنگسازی',
        'آواز جمعی',
        'سلفژ',
        'همنوازی',
        'ارکستر',
        'هارمونی',
        'آکوستیک',
      ],
      14 => [
        'سنتی',
        'پاپ',
        'محلی',
        'کلاسیک',
      ],
      15 => [
        'ساز سازی',
        'موسیقی کودک',
      ],
      16 => [
        'اتنوموزیکولوژی',
        'موزیکولوژی',
      ],
    ];

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_MHA_Member_Specialty', [
        // 'mbrspcID',
        'mbrspcUUID',
        'mbrspcMemberID',
        'mbrspcSpecialtyID',
        'mbrspcDesc',
        'mbrspcCreatedAt',
        // 'mbrspcCreatedBy',
        'mbrspcUpdatedAt',
        // 'mbrspcUpdatedBy',
        // 'mbrspcRemovedAt',
        // 'mbrspcRemovedBy',
      ], $values, $lastID, $convertKey);
    };

    //-----------------------
    $values = [];

    $fnCreateMbrSpcs = function($lastID, $row, $fldID) use(&$values, $keywords, &$specialties) {

      $userid = $row['tbl_profile_id'];

      if ($fldID == 7)
        $expertData_8 = trim($row['tbl_expert_fld8']);

      $expertData = trim($row['tbl_expert_fld' . $fldID]);
      if (empty($expertData)) {
        if (empty($expertData_8))
          return;

        $expertData = 'سایر';
      }

      //------------
      if (in_array($fldID, [9, 10, 11])) {
        $spctext = $this->quotedString(Json::encode([
          'desc' => $expertData,
        ]));

        $values[$lastID] = implode(',', [
          // /* mbrspcID          */ $lastID,
          /* mbrspcUUID        */ 'UUID()',
          /* mbrspcMemberID    */ $userid + 100,
          /* mbrspcSpecialtyID */ $fldID,
          /* mbrspcDesc        */ $spctext,
          /* mbrspcCreatedAt   */ $this->jalaliToMiladi($row['tbl_profile_date'], 'NOW()'),
          /* mbrspcCreatedBy   */
          /* mbrspcUpdatedAt   */ $this->jalaliToMiladi($row['tbl_profile_editdate']),
          /* mbrspcUpdatedBy   */
          /* mbrspcRemovedAt   */
          /* mbrspcRemovedBy   */
        ]);

        return;
      }

      //-- other than 9, 10, 11
      $spcid = null;

      if (strpos($expertData, '*') === false) {
        $expertDataParts = [];

        foreach ($keywords[$fldID] as $keyword) {
          $idx = strpos($expertData, $keyword);
          if ($idx === false)
            continue;

          $expertDataParts[] = $keyword;

          $tmp = $expertData;
          $expertData = '';
          if ($idx > 0)
            $expertData = substr($tmp, 0, $idx);

          $idx += strlen($keyword);
          if ($idx < strlen($tmp))
            $expertData .= substr($tmp, $idx);

          $expertData = trim($expertData);

          if (empty($expertData))
            break;
        }

        if (empty($expertData) == false) {
          //OOPS!
          echo "  REMAINED EXPERT DATA: " . $expertData . "\n";

          $expertDataParts[] = $expertData;
        }
      } else {
        $expertDataParts = array_filter(explode('*', $expertData));
      }

      if (empty($expertDataParts))
        return;

      foreach ($expertDataParts as &$expertData) {
        $expertData = StringHelper::fixPersianCharacters(trim($expertData));

        $found = null;
        if (isset($specialties[$fldID]['items'])) {
          foreach ($specialties[$fldID]['items'] as $item) {
            if ($item['spcName'] == $expertData) {
              $found = $item['spcID'];
              break;
            }
          }
        }

        if ($found === null) {
          if (empty($specialties[$fldID]['model']))
            $specialties[$fldID]['model'] = SpecialtyModel::findOne($fldID);

          $child = new SpecialtyModel;
          $child->spcName = $expertData;
          if ($fldID == 7)
            $child->spcDescFieldType = 'text';

          if ($child->appendTo($specialties[$fldID]['model']) == false)
            throw new \Exception("error in creating spc fld:" . $fldID . ", name:" . $expertData);

          $specialties[$fldID]['items'] = array_merge($specialties[$fldID]['items'] ?? [], [
            $child->spcID => [
              'spcID' => $child->spcID,
              'spcRoot' => $child->spcRoot,
              'spcName' => $child->spcName,
              'spcDescFieldType' => $child->spcDescFieldType,
            ],
          ]);

          $found = $child->spcID;
        }

        $spctext = 'NULL';
        if (empty($expertData_8) == false) {
          $spctext = $this->quotedString(Json::encode([
            'desc' => $expertData_8,
          ]));
        }

        $values[$lastID] = implode(',', [
          // /* mbrspcID          */ $lastID,
          /* mbrspcUUID        */ 'UUID()',
          /* mbrspcMemberID    */ $userid + 100,
          /* mbrspcSpecialtyID */ $found,
          /* mbrspcDesc        */ $spctext,
          /* mbrspcCreatedAt   */ $this->jalaliToMiladi($row['tbl_profile_date'], 'NOW()'),
          /* mbrspcCreatedBy   */
          /* mbrspcUpdatedAt   */ $this->jalaliToMiladi($row['tbl_profile_editdate']),
          /* mbrspcUpdatedBy   */
          /* mbrspcRemovedAt   */
          /* mbrspcRemovedBy   */
        ]);
      }
    };

    $fetchCount = 1000;
    $saveCount = 100;
    $loopCount = 0;
    while (true) {
      ++$loopCount;

      $qry =<<<SQL
      SELECT *
        FROM tbl_expert
  INNER JOIN tbl_profile
          ON tbl_profile.tbl_profile_systemcode = tbl_expert.tbl_expert_systemcode
      WHERE tbl_expert.tbl_expert_id > {$lastID}
    ORDER BY tbl_expert.tbl_expert_id

      LIMIT {$fetchCount}
SQL;

      $this->log("  fetching data from ({$lastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_expert_id']);

        $fnCreateMbrSpcs($lastID, $row, 1);
        $fnCreateMbrSpcs($lastID, $row, 2);
        $fnCreateMbrSpcs($lastID, $row, 3);
        $fnCreateMbrSpcs($lastID, $row, 4);
        $fnCreateMbrSpcs($lastID, $row, 5);
        $fnCreateMbrSpcs($lastID, $row, 6);
        $fnCreateMbrSpcs($lastID, $row, 7);
        // $fnCreateMbrSpcs($lastID, $row, 8);
        $fnCreateMbrSpcs($lastID, $row, 9);
        $fnCreateMbrSpcs($lastID, $row, 10);
        $fnCreateMbrSpcs($lastID, $row, 11);
        $fnCreateMbrSpcs($lastID, $row, 12);
        $fnCreateMbrSpcs($lastID, $row, 13);
        $fnCreateMbrSpcs($lastID, $row, 14);
        $fnCreateMbrSpcs($lastID, $row, 15);
        $fnCreateMbrSpcs($lastID, $row, 16);

        if (count($values) >= $saveCount) {
          $fnPutData($values, $lastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $lastID);
        $values = [];
      }

    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_profile_to_UserImage(&$convertTableData)
  {
    $this->log("profile to User Image:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->user-image';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 2; //start from 3
    $lastSavedID = 0;

    $tmpPath = Yii::$app->params['convert_source_files_path_user'];

    $fetchCount = 1000;
    $loopCount = 0;
    while (true) {
      ++$loopCount;

      $qry =<<<SQL
    SELECT tbl_profile.tbl_profile_id
         , tbl_profile.tbl_profile_systemcode
         , tbl_profile.tbl_profile_img
      FROM tbl_profile
     WHERE tbl_profile.tbl_profile_id > {$lastID}
       AND TRIM(IFNULL(tbl_profile.tbl_profile_img, '')) != ''
  ORDER BY tbl_profile.tbl_profile_id
     LIMIT {$fetchCount}
SQL;

      $this->log("  fetching data from ({$lastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_profile_id']);

        $originalFileName = trim($row['tbl_profile_img']);
        $sourceFileName = $tmpPath . $originalFileName;

        $this->log("  {$lastID} > {$originalFileName}");

        if (file_exists($sourceFileName) == false) {
          $this->log("    SKIP: file not found");
          continue;
        }

        $uuid = trim($row['tbl_profile_systemcode']);
        $userid = $lastID + 100;
        $checkImgUsed = false;

        //check tbl_AAA_UploadFile
        $qry =<<<SQL
  SELECT *
    FROM tbl_AAA_UploadFile
   WHERE uflOriginalFileName = '{$originalFileName}'
SQL;
        $fileRow = $this->queryOne($qry, __FUNCTION__, __LINE__);
        if (empty($fileRow) == false) {
          $imageFileID = $fileRow['uflID'];
          $checkImgUsed = true;
        } else
          $imageFileID = null;

        if (empty($imageFileID)) {
          $imageFileID = Yii::$app->fileManager->saveAndUploadFileForUser(
            /* sourceFileName     */ $sourceFileName,
            /* sourceIsFromUpload */ false,
            /* originalFileName   */ $originalFileName,
            /* owner_uuid         */ $uuid,
            /* owner_id           */ $userid,
            /* subdir             */ 'user',
            /* overwrite          */ true,
            /* doStore            */ false,
            /* deleteLocalFileAfterUpload */ true
          );
        }

        //------------
        if ($checkImgUsed) {
          $qry =<<<SQL
  SELECT *
    FROM tbl_AAA_User
   WHERE usrImageFileID = {$imageFileID}
SQL;
          $userRow = $this->queryOne($qry, __FUNCTION__, __LINE__);
          if (empty($userRow) == false) {
            $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
       VALUES ('{$convertKey}', $lastID, NOW())
           ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //----------------------
            $this->log("    SKIP: already converted and assigned to the user");
            continue;
          }
          $this->log("    reassign file to the user");
        }

        //tbl_aaa_user
        $qry =<<<SQL
  UPDATE tbl_AAA_User
     SET usrImageFileID = {$imageFileID}
   WHERE usrID = {$userid}
SQL;
        $this->queryExecute($qry, __FUNCTION__, __LINE__);

        //tbl_convert
        $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
       VALUES ('{$convertKey}', $lastID, NOW())
           ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
        $this->queryExecute($qry, __FUNCTION__, __LINE__);

        $lastSavedID = $lastID;

      } //foreach ($rows as $row)

    } //while (true)

    // if ($lastSavedID != $lastID) {
      //tbl_convert
      $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
       VALUES ('{$convertKey}', $lastID, NOW())
           ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
    // }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_document_to_Mbr_Document(&$convertTableData)
  {
    $this->log("document to Mbr_Document:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_document->member-document';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

    $tmpPath = Yii::$app->params['convert_source_files_path_document'];

    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_MHA_Member_Document', [
//        'mbrdocID',
        'mbrdocUUID',
        'mbrdocMemberID',
        'mbrdocDocumentID',
        'mbrdocTitle',
        'mbrdocFileID',
        'mbrdocStatus',
        'mbrdocCreatedAt',
        // 'mbrdocCreatedBy',
        // 'mbrdocUpdatedAt',
        // 'mbrdocUpdatedBy',
      ], $values, $lastID, $convertKey);
    };

    // $lastID = 0;
    // $lastSavedID = 0;

    //--------------------
    $newDocumentTypeIDs = [];
		$qry =<<<SQL
    SELECT *
      FROM tbl_MHA_Document
SQL;
    $docrows = $this->queryAll($qry, __FUNCTION__, __LINE__);
    foreach ($docrows as $docrow) {
      $newDocumentTypeIDs[$docrow['docName']] = $docrow['docID'];
    }

    //--------------------
    $fetchCount = 500;
    $loopCount = 0;
    $maxLoopCount = 1;
    while (true) {
      ++$loopCount;

      if (($maxLoopCount > 0) && ($loopCount > $maxLoopCount))
        break;

		$qry =<<<SQL
      SELECT tbl_document.*
           , tbl_profile.tbl_profile_id
           , tbl_profile.tbl_profile_systemcode
           , tbl_categories.*
        FROM tbl_document
  INNER JOIN tbl_profile
          ON tbl_profile.tbl_profile_systemcode = tbl_document.tbl_document_userid
   LEFT JOIN tbl_categories
          ON TRIM(tbl_categories.tbl_categories_title)
             = IF(TRIM(tbl_document.tbl_document_section) = 'صفحه اصلی شناسنامه'
              , 'صفحه اول شناسنامه'
              , TRIM(tbl_document.tbl_document_section))
         AND tbl_categories.tbl_categories_type = 10
       WHERE tbl_document.tbl_document_id > {$lastID}
    ORDER BY tbl_document.tbl_document_id
       LIMIT {$fetchCount}
SQL;

      $this->log("  fetching data from ({$lastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_document_id']);

        //------------
        $docid = null;

        if (empty($row['tbl_categories_id'])) {
          $title = StringHelper::fixPersianCharacters(trim($row['tbl_document_section']));

          if (isset($newDocumentTypeIDs[$title]))
            $docid = $newDocumentTypeIDs[$title];
          else {
            $qry =<<<SQL
  INSERT INTO tbl_MHA_Document(docUUID, docName, docType)
       VALUES (UUID(), '{$title}', 'O');
SQL;

            if ($this->queryExecute($qry, __FUNCTION__, __LINE__) == 0)
              throw new \Exception('could not create new document type');

            $docid = Yii::$app->db->getLastInsertID();
            $newDocumentTypeIDs[$title] = $docid;

            $this->log("  document type ({$title}) created at ({$docid})");
          }
        } else
          $docid = $row['tbl_categories_id'];

        //------------
        $originalFileName = trim($row['tbl_document_file']);
        $sourceFileName = $tmpPath . $originalFileName;

        $this->log("  {$lastID} > {$originalFileName}");

        if (file_exists($sourceFileName) == false) {
          $this->log("    SKIP: file not found");
          continue;
        }

        $uuid = trim($row['tbl_profile_systemcode']);
        $userid = trim($row['tbl_profile_id']);
        $userid = $userid + 100;
        $checkImgUsed = false;

        //check tbl_AAA_UploadFile
        $qry =<<<SQL
  SELECT *
    FROM tbl_AAA_UploadFile
   WHERE uflOriginalFileName = '{$originalFileName}'
SQL;
        $fileRow = $this->queryOne($qry, __FUNCTION__, __LINE__);
        if (empty($fileRow) == false) {
          $imageFileID = $fileRow['uflID'];
          $checkImgUsed = true;
        } else
          $imageFileID = null;

        if (empty($imageFileID)) {
          $imageFileID = Yii::$app->fileManager->saveAndUploadFileForUser(
            /* sourceFileName     */ $sourceFileName,
            /* sourceIsFromUpload */ false,
            /* originalFileName   */ $originalFileName,
            /* owner_uuid         */ $uuid,
            /* owner_id           */ $userid,
            /* subdir             */ 'document',
            /* overwrite          */ true,
            /* doStore            */ false,
            /* deleteLocalFileAfterUpload */ true
          );
        }

        //------------
        if ($checkImgUsed) {
          $qry =<<<SQL
  SELECT *
    FROM tbl_MHA_Member_Document
   WHERE mbrdocFileID = {$imageFileID}
SQL;
          $docRow = $this->queryOne($qry, __FUNCTION__, __LINE__);
          if (empty($docRow) == false) {
            $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
       VALUES ('{$convertKey}', $lastID, NOW())
           ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //----------------------
            $this->log("    SKIP: already converted and exists in mbrdoc");
            continue;
          }
          $this->log("    reassign file to mbrdoc");
        }

        $fnPutData(implode(',', [
//          /* mbrdocID         */ $lastID,
          /* mbrdocUUID       */ 'UUID()',
          /* mbrdocMemberID   */ $userid,
          /* mbrdocDocumentID */ $docid,
          /* mbrdocTitle      */ $this->quotedString($row['tbl_document_title']),
          /* mbrdocFileID     */ $imageFileID,
          /* mbrdocStatus     */ "'" . (trim($row['tbl_document_status']) == '0'
                                  ? enuMemberDocumentStatus::WaitForApprove
                                  : enuMemberDocumentStatus::Approved) . "'",
          /* mbrdocCreatedAt  */ $this->jalaliToMiladi($row['tbl_document_date'], 'NOW()'),
          /* mbrdocCreatedBy  */
          /* mbrdocUpdatedAt  */
          /* mbrdocUpdatedBy  */
        ]), $lastID);

        $lastSavedID = $lastID;

      } //foreach ($rows as $row)

    } //while (true)

    // if ($lastSavedID != $lastID) {
      //tbl_convert
      $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
       VALUES ('{$convertKey}', $lastID, NOW())
           ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
    // }

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function ensureMembershipSaleableExists()
  {
    $this->log("ensureMembershipSaleableExists");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $qry =<<<SQL
  INSERT IGNORE INTO tbl_MHA_Accounting_Unit
     SET untID       = 1
       , untUUID     = UUID()
       , untName     = 'سال'
       , untI18NData = '{"en": {"untName": "Year"}}'
SQL;
    $this->queryExecute($qry, __FUNCTION__, __LINE__);

    $qry =<<<SQL
  INSERT IGNORE INTO tbl_MHA_Accounting_Product
     SET prdID      = 1
       , prdUUID    = UUID()
       , prdName    = 'حق عضویت سالیانه'
       , prdType    = 'D'
       , prdUnitID  = 1
       , prdMhaType = 'M'
SQL;
    $this->queryExecute($qry, __FUNCTION__, __LINE__);

    $qry =<<<SQL
  INSERT IGNORE INTO tbl_MHA_Accounting_Saleable
     SET slbID                = 1
       , slbUUID              = UUID()
       , slbProductID         = 1
       , slbCode              = UUID()
       , slbName              = 'پیش فرض برای کانورت اطلاعات'
       , slbAvailableFromDate = '1921/03/21 00:00:00'
       , slbBasePrice         = 50000
SQL;
    $this->queryExecute($qry, __FUNCTION__, __LINE__);

    return 1;
  }

  public function convert_billing(&$convertTableData)
  {
    $this->log("billing:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    //-- update AUTO_INCREMENT
    $qry =<<<SQL
  SELECT MAX(tbl_billing_id) AS cnt
    FROM tbl_billing
SQL;
    $cnt1 = $oldcrmdbv2->createCommand($qry)->queryOne();
    if (empty($cnt1) || (($cnt1['cnt'] ?? 0) == 0)) {
      $this->log("  nothing to do");
      return;
    }
    $cnt1 = $cnt1['cnt'];

    //--
/*
  SELECT AUTO_INCREMENT
    FROM INFORMATION_SCHEMA.TABLES
   WHERE TABLE_SCHEMA = DATABASE()
     AND TABLE_NAME   = 'tbl_AAA_OfflinePayment';
*/
    $qry =<<<SQL
  SELECT MAX(ofpID) AS cnt
    FROM tbl_AAA_OfflinePayment
SQL;
    $cnt2 = $this->queryOne($qry, __FUNCTION__, __LINE__);
    if (empty($cnt2) || (($cnt2['cnt'] ?? 0) == 0))
      $cnt2 = 0;
    else
      $cnt2 = $cnt2['cnt'];

    //--
    ++$cnt1;
    if ($cnt2 < $cnt1) {
      $qry = "ALTER TABLE tbl_AAA_OfflinePayment AUTO_INCREMENT={$cnt1};";
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
      $this->log("  AUTO_INCREMENT changed to " . $cnt1);
    }

    //-- normalize input -------------------------------
    $qry =<<<SQL
  SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE()
     AND TABLE_NAME   = 'tbl_billing'
     AND COLUMN_NAME  = 'convert_tbl_billing_username';
SQL;
    $cnt1 = $oldcrmdbv2->createCommand($qry)->queryOne();
    if (empty($cnt1)) {
      $qry =<<<SQL
ALTER TABLE `tbl_billing`
  ADD COLUMN `convert_tbl_billing_username` VARCHAR(256) NULL DEFAULT NULL AFTER `tbl_billing_username`,
  ADD INDEX `convert_tbl_billing_username` (`convert_tbl_billing_username`);
SQL;
      $oldcrmdbv2->createCommand($qry)->execute();
    }

    $qry =<<<SQL
  UPDATE tbl_billing
     SET tbl_billing_title = TRIM(tbl_billing_title)
       , tbl_billing_usercode = TRIM(tbl_billing_usercode)
       , convert_tbl_billing_username =
          REPLACE(
          REPLACE(
          REPLACE(
          REPLACE(
          REPLACE(
          REPLACE(
          REPLACE(
          REPLACE(TRIM(tbl_billing_username),
            'أ', 'ا'),
            'ك', 'ک'),
            'ؠ', 'ی'),
            'ى', 'ی'),
            'ي', 'ی'),
            'ݷ', 'ی'),
            'ئ', 'ی'),
            ' ', '')
SQL;
    $oldcrmdbv2->createCommand($qry)->execute();

    //-- add convert_fullname to tbl_profile -------------------------------
    $qry =<<<SQL
  SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE()
     AND TABLE_NAME   = 'tbl_profile'
     AND COLUMN_NAME  = 'convert_fullname';
SQL;
    $cnt1 = $oldcrmdbv2->createCommand($qry)->queryOne();
    if (empty($cnt1)) {
      $qry =<<<SQL
ALTER TABLE `tbl_profile`
  ADD COLUMN `convert_fullname` VARCHAR(1024) NULL DEFAULT NULL AFTER `tbl_profile_fld2`,
  ADD INDEX `convert_fullname` (`convert_fullname`);
SQL;
      $oldcrmdbv2->createCommand($qry)->execute();
    }

    $qry =<<<SQL
  UPDATE tbl_profile
     SET convert_fullname =
      REPLACE(
      REPLACE(
      REPLACE(
      REPLACE(
      REPLACE(
      REPLACE(
      REPLACE(
      REPLACE(CONCAT(tbl_profile_fld1, ' ', tbl_profile_fld2),
        'أ', 'ا'),
        'ك', 'ک'),
        'ؠ', 'ی'),
        'ى', 'ی'),
        'ي', 'ی'),
        'ݷ', 'ی'),
        'ئ', 'ی'),
        ' ', '')
SQL;
  //  WHERE convert_fullname IS NULL
    $oldcrmdbv2->createCommand($qry)->execute();

    $membershipID = $this->ensureMembershipSaleableExists();

    //---------------------------------
    $fnGetConst = function($value) { return $value; };

    $convertKey = 'v2.tbl_billing';
    $queryLastID = $convertTableData[$convertKey]['lastID'] ?? 0;

    //-----------------
    $errorids = $convertTableData[$convertKey]['info'] ?? null;
    if (empty($errorids))
      $errorids = [];
    else
      $errorids = explode(',', $errorids);

    if (empty($errorids) == false) {
      $errorids = array_combine(array_values($errorids), array_values($errorids));
      $this->log("  last errorids: " . implode(',', $errorids));
    }

    $processedErrorIds = [];

    $fnRemoveFromErrorIDs = function($lastID) use (&$errorids, &$processedErrorIds) {
      if (empty($errorids[$lastID]))
        return false;

      unset($errorids[$lastID]);
      $processedErrorIds[$lastID] = $lastID;

      return true;
    };

    //-----------------
    $userDefWalletMap = [];
    $fetchCount = 1000;
    $loopCount = 0;

    while (true) {
      ++$loopCount;

      // if ($loopCount > 1)
      //   break;

      $erroridsCount = count($errorids);
      $newFetchCount = $fetchCount;
      if ($erroridsCount > $newFetchCount)
        $newFetchCount += $erroridsCount;

      $where = "tbl_billing.tbl_billing_id > {$queryLastID}";
      if (empty($errorids) == false) {
        $where = '(' . $where . "\nOR tbl_billing.tbl_billing_id IN (" . implode(',', $errorids) . ")\n)";
      }
      $where .= "\n";
      if (empty($processedErrorIds) == false) {
        $where .= "AND tbl_billing.tbl_billing_id NOT IN (" . implode(',', $processedErrorIds) . ")\n";
      }

      $qry =<<<SQL
     SELECT p1.tbl_profile_id AS p1_id_by_code
          , GROUP_CONCAT(p2.tbl_profile_id) AS p2_id_by_name
          , p3.tbl_profile_id AS p3_id_by_title
          , tbl_billing.*

       FROM tbl_billing

  LEFT JOIN tbl_profile p1
         ON p1.tbl_profile_code = tbl_billing.tbl_billing_usercode
        AND tbl_billing.tbl_billing_usercode != '0'
        AND tbl_billing.tbl_billing_usercode REGEXP '^[0-9]+$'

  LEFT JOIN tbl_profile p2
         ON p2.convert_fullname = tbl_billing.convert_tbl_billing_username

  LEFT JOIN tbl_profile p3
         ON p3.tbl_profile_code = tbl_billing.tbl_billing_title
        AND tbl_billing.tbl_billing_title != '1'
        AND tbl_billing.tbl_billing_title REGEXP '^[0-9]+$'

      WHERE {$where}

   GROUP BY tbl_billing_id

      LIMIT {$newFetchCount}
SQL;

      $this->log("  fetching data from ({$queryLastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched: " . count($rows) . " rows");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_billing_id']);

        if ($lastID > $queryLastID)
          $queryLastID = $lastID;

        //------------
        try {
          $profileID = null;

          $tbl_billing_title     = $this->quotedString($row['tbl_billing_title']);
          // $tbl_billing_usercode
          $tbl_billing_username  = $this->quotedString($row['tbl_billing_username']);
          $tbl_billing_bank      = $this->quotedString($row['tbl_billing_bank']);
          $tbl_billing_price     = intval(preg_replace('/[^0-9]/', '', $row['tbl_billing_price'])) / 10; //rial -> toman
          $tbl_billing_track     = $this->quotedString($row['tbl_billing_track']);
          $tbl_billing_date      = $this->jalaliToMiladi($row['tbl_billing_date']);
          $tbl_billing_comment   = $this->quotedString($row['tbl_billing_comment']);
          $tbl_billing_status    = intval(preg_replace('/[^0-9]/', '', $row['tbl_billing_status']));
          $tbl_billing_type      = intval(preg_replace('/[^0-9]/', '', $row['tbl_billing_type']));
          $tbl_billing_expire_jalali = trim($row['tbl_billing_expire']);
          $tbl_billing_expire    = $this->jalaliToMiladi($row['tbl_billing_expire']);
          $tbl_billing_editdate  = $this->jalaliToMiladi($row['tbl_billing_editdate']);

          if (($tbl_billing_price > 0)
              && ($tbl_billing_date != 'NULL')
              && ($tbl_billing_expire != 'NULL')
          ) {
            $p1_id_by_code  = $row['p1_id_by_code'] ?? null;
            $p2_id_by_name  = $row['p2_id_by_name'] ?? null;
            $p3_id_by_title = $row['p3_id_by_title'] ?? null;

            if ($p1_id_by_code != null) {
              if ($p2_id_by_name != null) {
                if ($p1_id_by_code == $p2_id_by_name) {
                  $profileID = $p1_id_by_code;
                } else  {
                  if ($p3_id_by_title != null) {
                    if (strpos(",{$p2_id_by_name},", ",{$p3_id_by_title},") !== false) {
                      $profileID = $p3_id_by_title;
                    }
                  }
                }
              } else if ($p3_id_by_title != null
                && $p1_id_by_code == $p3_id_by_title
              ) {
                $profileID = $p1_id_by_code;
              } else if ($p3_id_by_title == null) {
                $profileID = $p1_id_by_code;
              }
            }  //if ($p1_id_by_code != null)
            else { //$p1_id_by_code == null
              if ($p2_id_by_name != null) {
                if (strpos($p2_id_by_name, ',') === false) {
                  $profileID = $p2_id_by_name;
                } else {
                  if ($p3_id_by_title != null) {
                    if (strpos(",{$p2_id_by_name},", ",{$p3_id_by_title},") !== false) {
                      $profileID = $p3_id_by_title;
                    }
                  }
                  //else error
                }
              }
              //else error
            } //$p1_id_by_code == null
          } //if ($tbl_billing_date != 'NULL')

          //has error?
          $err = [];

          if ($profileID != null) {
            $doSwap = false;

            if ($tbl_billing_track == 'NULL') {
              if ($tbl_billing_title == 'NULL') {
                $err[] = 'track and title is null';
                $profileID = null;
              } else
                $doSwap = true;
            } else {
//               if ($tbl_billing_title != 'NULL') {
//                 $uid = $profileID + 100;
//                 $qry =<<<SQL
//   SELECT *
//     FROM tbl_MHA_Member
//    WHERE mbrRegisterCode = {$tbl_billing_track}
//      AND mbrUserID = {$uid}
// SQL;
//                 $mbrrow = $this->queryOne($qry, __FUNCTION__, __LINE__);
//                 if (empty($mbrrow) == false)
//                   $doSwap = true;
//               }
            }
            if ($doSwap) {
              $this->log("  swap track and title (ofp:{$lastID})");
              $swp = $tbl_billing_track;
              $tbl_billing_track = $tbl_billing_title;
              $tbl_billing_title = $swp;
            }
          }

          if ($profileID === null) {
            if (empty($tbl_billing_price))
              $err[] = 'price';
            if ($tbl_billing_date == 'NULL')
              $err[] = 'date';
            if ($tbl_billing_expire == 'NULL')
              $err[] = 'expire';
            $err = implode(',', $err);

            $this->log("  ERROR ON '{$lastID}' {$err}");

            $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at, info)
       VALUES ('{$convertKey}', 0, NOW(), '{$lastID}')
           ON DUPLICATE KEY UPDATE info = IF (info IS NULL OR LENGTH(info)=0,
                '{$lastID}',
                IF (LOCATE(',{$lastID},', CONCAT(',', info, ',')) >= 1,
                  info,
                  CONCAT(info, ',', '{$lastID}')
                )
              )
            , at=NOW();
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //prevent fetch in next loops
            $fnRemoveFromErrorIDs($lastID);

            continue;
          }

          //----------
          $userid = $profileID + 100;

          $transaction = Yii::$app->db->beginTransaction();

          try {
            //search for duplicates
            $qry =<<<SQL
  SELECT COUNT(*) AS cnt
    FROM tbl_AAA_OfflinePayment
   WHERE ofpTrackNumber = {$tbl_billing_track}
     AND ofpPayDate = {$tbl_billing_date}
SQL;
            $duprow = $this->queryOne($qry, __FUNCTION__, __LINE__);
            $duplicate = false;
            if (empty($duprow) == false && ($duprow['cnt'] ?? 0 > 0)) {
              $this->log("  DUPLICATE (ofp:{$lastID})");
              $duplicate = true;
            }

            //phase 1: offline payment
            //ensure wallet
            if (empty($userDefWalletMap[$userid])) {
              $qry =<<<SQL
  SELECT walID
    FROM tbl_AAA_Wallet
   WHERE walOwnerUserID = {$userid}
     AND walIsDefault = 1
     AND walStatus != '{$fnGetConst(enuWalletStatus::Removed)}'
SQL;
              $walrow = $this->queryOne($qry, __FUNCTION__, __LINE__);
              if (empty($walrow) || (($walrow['walID'] ?? 0) == 0)) {
                $this->log("  error in get def wal id");
                throw new \Exception("  error in get def wal id");
              }
              $walid = $walrow['walID'];
              $userDefWalletMap[$userid] = $walid;
            } else
              $walid = $userDefWalletMap[$userid];

            //create credit voucher
            if ($tbl_billing_comment == 'NULL')
              $ofpComment = [];
            else
              $ofpComment = [$tbl_billing_comment];

            $ofpComment[] = "'انقضا: {$tbl_billing_expire_jalali}'";

            if ($duplicate) {
              $ofpStatus = enuOfflinePaymentStatus::Rejected;
              $voucherid = 'NULL';

              $ofpComment[] = "'تکراری'";
            } else {
              $ofpStatus = enuOfflinePaymentStatus::Approved;

              $qry =<<<SQL
  INSERT INTO tbl_AAA_Voucher
          SET vchUUID        = UUID()
            , vchOwnerUserID = {$userid}
            , vchType        = '{$fnGetConst(enuVoucherType::Credit)}'
            , vchAmount      = {$tbl_billing_price}
            , vchTotalAmount = {$tbl_billing_price}
            , vchOfflinePaid = {$tbl_billing_price}
            , vchItems       = '{"inc-wallet-id":"{$walid}"}'
            , vchStatus      = '{$fnGetConst(enuVoucherStatus::Finished)}'
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);
              $voucherid = Yii::$app->db->getLastInsertID();
            }

            $ofpComment = "CONCAT(" . implode(", ' - ', ", $ofpComment) . ")";

            //create offline payment
            $qry =<<<SQL
  INSERT INTO tbl_AAA_OfflinePayment
          SET ofpID               = {$lastID}
            , ofpUUID             = UUID()
            , ofpOwnerUserID      = {$userid}
            , ofpVoucherID        = {$voucherid}
            , ofpBankOrCart       = {$tbl_billing_bank}
            , ofpTrackNumber      = {$tbl_billing_track}
            , ofpReferenceNumber  = {$tbl_billing_title}
            , ofpAmount           = {$tbl_billing_price}
            , ofpPayDate          = {$tbl_billing_date}
            , ofpPayer            = {$tbl_billing_username}
            , ofpSourceCartNumber = NULL
            , ofpImageFileID      = NULL
            , ofpWalletID         = {$walid}
            , ofpComment          = {$ofpComment}
            , ofpStatus           = '{$ofpStatus}'
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);
            $ofpid = Yii::$app->db->getLastInsertID();

            if ($duplicate == false) {
              //create wallet transaction
              $qry =<<<SQL
  INSERT INTO tbl_AAA_WalletTransaction
          SET wtrUUID             = UUID()
            , wtrWalletID		      = {$walid}
            , wtrVoucherID		    = {$voucherid}
            , wtrOfflinePaymentID = {$ofpid}
            , wtrAmount			      = {$tbl_billing_price}
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //update wallet
//               $qry =<<<SQL
//   UPDATE tbl_AAA_Wallet
//      SET walRemainedAmount = walRemainedAmount + {$tbl_billing_price}
//    WHERE walID = {$walid}
// SQL;
//               $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //phase 2: membership
              //create basket voucher
              $vchItemKey = Uuid::uuid4()->toString();
              $vchItems = Json::encode([
                [
                  'key'       => $vchItemKey,
                  // 'userid'    => $userid,
                  'service'   => 'mha',
                  // 'slbkey'    => 'mbrshp',
                  'slbid'     => $membershipID,
                  'desc'      => 'حق عضویت تا ' . $tbl_billing_expire_jalali,
                  'qty'       => 1,
                  'unit'			=> 'سال',
                  'prdtype'		=> 'D',
                  'unitprice' => $tbl_billing_price,
                  // 'slbinfo'		=> [
                  //   'startDate' => $startDate,
                  //   'endDate' => $endDate,
                  // ],
                  'maxqty'    => 1,
                  'qtystep'		=> 0, //0: do not allow to change qty in basket
                ],
              ]);

              $qry =<<<SQL
  INSERT INTO tbl_AAA_Voucher
          SET vchUUID         = UUID()
            , vchOwnerUserID  = {$userid}
            , vchType         = '{$fnGetConst(enuVoucherType::Basket)}'
            , vchAmount       = {$tbl_billing_price}
            , vchTotalAmount  = {$tbl_billing_price}
            , vchPaidByWallet = {$tbl_billing_price}
            , vchTotalPaid    = {$tbl_billing_price}
            , vchItems        = '{$vchItems}'
            , vchStatus       = '{$fnGetConst(enuVoucherStatus::Finished)}'
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);
              $voucherid = Yii::$app->db->getLastInsertID();

              //create wallet transaction
              $qry =<<<SQL
  INSERT INTO tbl_AAA_WalletTransaction
          SET wtrUUID       = UUID()
            , wtrWalletID		= {$walid}
            , wtrVoucherID	= {$voucherid}
            , wtrAmount			= (-1) * {$tbl_billing_price}
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //update wallet
  //             $qry =<<<SQL
  //   UPDATE tbl_AAA_Wallet
  //     SET walRemainedAmount = walRemainedAmount - {$tbl_billing_price}
  //   WHERE walID = {$walid}
  // SQL;
  //             $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //member membership
              $qry =<<<SQL
  INSERT INTO tbl_MHA_Accounting_UserAsset
          SET uasUUID						 = '{$vchItemKey}'
            , uasActorID         = {$userid}
            , uasSaleableID      = {$membershipID}
            , uasQty             = 1
            , uasVoucherID       = {$voucherid}
            , uasVoucherItemInfo = '{$vchItems}'
            , uasValidFromDate   = NULL
            , uasValidToDate     = {$tbl_billing_expire}
            , uasStatus          = '{$fnGetConst(enuUserAssetStatus::Active)}'
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //phase 3: member
              $qry =<<<SQL
  UPDATE tbl_MHA_Member
     SET mbrAcceptedAt = DATE_ADD({$tbl_billing_expire}, INTERVAL -1 YEAR)
   WHERE mbrUserID = {$userid}
     AND (mbrAcceptedAt IS NULL
      OR mbrAcceptedAt > DATE_ADD({$tbl_billing_expire}, INTERVAL -1 YEAR)
         )
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);
            } //if ($duplicate == false)

            //phase 4: log
            //log to tbl_convert
            $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
        VALUES ('{$convertKey}', $lastID, NOW())
            ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //remove lastID from tbl_convert.info
            if ($fnRemoveFromErrorIDs($lastID)) {
              $this->log("  REMOVE '{$lastID}' FROM ERRORS");

              $qry =<<<SQL
  UPDATE tbl_convert
     SET info = IF (info IS NULL OR LENGTH(info)=0,
           NULL,
           IF (LOCATE(',{$lastID},', CONCAT(',', info, ',')) >= 1,
             TRIM(BOTH ',' FROM REPLACE(CONCAT(',', info, ','), ',{$lastID},', ',')),
             info
           )
         )
       , at=NOW()
   WHERE tableName = '{$convertKey}';
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);
            }

            //commit
            $transaction->commit();

          } catch (\Throwable $exp) {
            $transaction->rollBack();
            throw $exp;
          }

        } catch (\Throwable $exp) {
          echo "** ERROR: ID: {$lastID} **\n";
          echo $exp->getMessage();
          echo "\n";
          throw $exp;
        }
      } //foreach ($rows as $row)

    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_onlinebank(&$convertTableData)
  {
    $this->log("onlinebank:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    //-- update AUTO_INCREMENT
    $qry =<<<SQL
  SELECT MAX(tbl_onlinebank_id) AS cnt
    FROM tbl_onlinebank
SQL;
    $cnt1 = $oldcrmdbv2->createCommand($qry)->queryOne();
    if (empty($cnt1) || (($cnt1['cnt'] ?? 0) == 0)) {
      $this->log("  nothing to do");
      return;
    }
    $cnt1 = $cnt1['cnt'];

    //--
  /*
  SELECT AUTO_INCREMENT
    FROM INFORMATION_SCHEMA.TABLES
   WHERE TABLE_SCHEMA = DATABASE()
     AND TABLE_NAME   = 'tbl_AAA_OnlinePayment';
  */
    $qry =<<<SQL
  SELECT MAX(onpID) AS cnt
    FROM tbl_AAA_OnlinePayment
SQL;
    $cnt2 = $this->queryOne($qry, __FUNCTION__, __LINE__);
    if (empty($cnt2) || (($cnt2['cnt'] ?? 0) == 0))
      $cnt2 = 0;
    else
      $cnt2 = $cnt2['cnt'];

    //--
    ++$cnt1;
    if ($cnt2 < $cnt1) {
      $qry = "ALTER TABLE tbl_AAA_OnlinePayment AUTO_INCREMENT={$cnt1};";
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
      $this->log("  AUTO_INCREMENT changed to " . $cnt1);
    }

    //-- get BankKeshavarziPaymentGateway -------------------------------
    $gatewayID = null;
    $qry =<<<SQL
  SELECT *
    FROM tbl_AAA_Gateway
   WHERE gtwPluginName = 'BankKeshavarziPaymentGateway'
   LIMIT 1
SQL;
    $gtwrow = $this->queryOne($qry, __FUNCTION__, __LINE__);
    if (empty($gtwrow)) {
      throw new \Exception('bank keshavarzi not found');
    }
    $gatewayID = intval($gtwrow['gtwID']);

    //-- check default membership -------------------------------
    $membershipID = $this->ensureMembershipSaleableExists();

    //---------------------------------
    $fnGetConst = function($value) { return $value; };

    $convertKey = 'v2.tbl_onlinebank';
    $queryLastID = $convertTableData[$convertKey]['lastID'] ?? 0;

    //-----------------
    $errorids = $convertTableData[$convertKey]['info'] ?? null;
    if (empty($errorids))
      $errorids = [];
    else
      $errorids = explode(',', $errorids);

    if (empty($errorids) == false) {
      $errorids = array_combine(array_values($errorids), array_values($errorids));
      $this->log("  last errorids: " . implode(',', $errorids));
    }

    $processedErrorIds = [];

    $fnRemoveFromErrorIDs = function($lastID) use (&$errorids, &$processedErrorIds) {
      if (empty($errorids[$lastID]))
        return false;

      unset($errorids[$lastID]);
      $processedErrorIds[$lastID] = $lastID;

      return true;
    };

    //-----------------
    $userDefWalletMap = [];
    $fetchCount = 1000;
    $loopCount = 0;

    while (true) {
      ++$loopCount;

      // if ($loopCount > 1)
      //   break;

      $erroridsCount = count($errorids);
      $newFetchCount = $fetchCount;
      if ($erroridsCount > $newFetchCount)
        $newFetchCount += $erroridsCount;

      $where = "tbl_onlinebank.tbl_onlinebank_id > {$queryLastID}";
      if (empty($errorids) == false) {
        $where = '(' . $where . "\nOR tbl_onlinebank.tbl_onlinebank_id IN (" . implode(',', $errorids) . ")\n)";
      }
      $where .= "\n";
      if (empty($processedErrorIds) == false) {
        $where .= "AND tbl_onlinebank.tbl_onlinebank_id NOT IN (" . implode(',', $processedErrorIds) . ")\n";
      }

      $qry =<<<SQL
     SELECT *
       FROM tbl_onlinebank
  LEFT JOIN tbl_profile
         ON tbl_profile.tbl_profile_systemcode = tbl_onlinebank.tbl_onlinebank_user

      WHERE {$where}

      LIMIT {$newFetchCount}
SQL;

      $this->log("  fetching data from ({$queryLastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_onlinebank_id']);

        if ($lastID > $queryLastID)
          $queryLastID = $lastID;

        //------------
        try {
          //tbl_onlinebank_id
          $tbl_onlinebank_rrn        = $this->quotedString($row['tbl_onlinebank_rrn']);
          $tbl_onlinebank_date       = $this->jalaliToMiladi($row['tbl_onlinebank_date']);
          $tbl_onlinebank_price      = intval(preg_replace('/[^0-9]/', '', $row['tbl_onlinebank_price'])) / 10; //rial -> toman
          //tbl_onlinebank_user
          $tbl_onlinebank_status     = intval($row['tbl_onlinebank_status']);
          // $tbl_onlinebank_dateupdate = $this->jalaliToMiladi($row['tbl_onlinebank_dateupdate']);
          $tbl_onlinebank_comment    = $this->quotedString($row['tbl_onlinebank_comment']);
          // $tbl_onlinebank_dateday    = $this->jalaliToMiladi($row['tbl_onlinebank_dateday']);
          $tbl_profile_id            = $row['tbl_profile_id'];
          $tbl_profile_expiredate_jalali = trim($row['tbl_profile_expiredate'] ?? '');
          $tbl_profile_expiredate    = $this->jalaliToMiladi($row['tbl_profile_expiredate']);

          $profileID = null;
          if ((empty($tbl_profile_id) == false)
            && ($tbl_onlinebank_price > 0)
            && ($tbl_onlinebank_date != 'NULL')
            && ($tbl_profile_expiredate != 'NULL')
          ) {
            $profileID = $tbl_profile_id;
          }

          //has error?
          if ($profileID === null) {
            $err = [];
            if (empty($tbl_onlinebank_price))
              $err[] = 'price';
            if ($tbl_onlinebank_date == 'NULL')
              $err[] = 'date';
            if ($tbl_profile_expiredate == 'NULL')
              $err[] = 'expire';
            $err = implode(',', $err);

            $this->log("  ERROR ON '{$lastID}' {$err}");
}
/*

delete from tbl_MHA_MemberMembership;

update tbl_AAA_Wallet set walRemainedAmount = 0 where walOwnerUserID > 100;

delete tbl_AAA_WalletTransaction
	from tbl_AAA_WalletTransaction
	inner join tbl_AAA_Wallet
	on tbl_AAA_Wallet.walID = tbl_AAA_WalletTransaction.wtrWalletID
	where walOwnerUserID > 100;

delete from tbl_AAA_OfflinePayment WHERE ofpOwnerUserID > 100;

delete from tbl_AAA_Voucher where vchOwnerUserID > 100;

delete from tbl_convert where tableName = 'v2.tbl_billing';













            $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at, info)
       VALUES ('{$convertKey}', 0, NOW(), '{$lastID}')
           ON DUPLICATE KEY UPDATE info = IF (info IS NULL OR LENGTH(info)=0,
                '{$lastID}',
                IF (LOCATE(',{$lastID},', CONCAT(',', info, ',')) >= 1,
                  info,
                  CONCAT(info, ',', '{$lastID}')
                )
              )
            , at=NOW();
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //prevent fetch in next loops
            $fnRemoveFromErrorIDs($lastID);

            continue;
          }

          //----------
          $userid = $profileID + 100;

          $transaction = Yii::$app->db->beginTransaction();

          try {
            //phase 1: online payment
            //ensure wallet
            if (empty($userDefWalletMap[$userid])) {
              $qry =<<<SQL
  SELECT walID
    FROM tbl_AAA_Wallet
   WHERE walOwnerUserID = {$userid}
     AND walIsDefault = 1
     AND walStatus != '{$fnGetConst(enuWalletStatus::Removed)}'
SQL;
              $walrow = $this->queryOne($qry, __FUNCTION__, __LINE__);
              if (empty($walrow) || (($walrow['walID'] ?? 0) == 0)) {
                $this->log("  error in get def wal id");
                throw new \Exception("  error in get def wal id");
              }
              $walid = $walrow['walID'];
              $userDefWalletMap[$userid] = $walid;
            } else
              $walid = $userDefWalletMap[$userid];

            //create credit voucher
            /*if ($tbl_onlinebank_status == 0) { //error
              $vchOnlinePaid = 'NULL';
              $vchStatus = enuVoucherStatus::Error;
            } else* / { //ok
              $vchOnlinePaid = $tbl_onlinebank_price;
              $vchStatus = enuVoucherStatus::Finished;
            }
            $qry =<<<SQL
  INSERT INTO tbl_AAA_Voucher
          SET vchUUID        = UUID()
            , vchOwnerUserID = {$userid}
            , vchType        = '{$fnGetConst(enuVoucherType::Credit)}'
            , vchAmount      = {$tbl_onlinebank_price}
            , vchTotalAmount = {$tbl_onlinebank_price}
            , vchOnlinePaid  = {$vchOnlinePaid}
            , vchItems       = '{"inc-wallet-id":"{$walid}"}'
            , vchStatus      = '{$vchStatus}'
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);
            $voucherid = Yii::$app->db->getLastInsertID();

            //create online payment
            /*if ($tbl_onlinebank_status == 0) { //error
              $onpStatus = enuOnlinePaymentStatus::Error;
            } else* / { //ok
              $onpStatus = enuOnlinePaymentStatus::Paid;
            }
            // , onpCallbackUrl      =
            // , onpResult           =
            $qry =<<<SQL
  INSERT INTO tbl_AAA_OnlinePayment
          SET onpID               = {$lastID}
            , onpUUID             = UUID()
            , onpGatewayID        = {$gatewayID}
            , onpVoucherID        = {$voucherid}
            , onpAmount           = {$tbl_onlinebank_price}
            , onpWalletID         = {$walid}
            , onpPaymentToken     = {$tbl_onlinebank_rrn}
            , onpRRN              = {$tbl_onlinebank_rrn}
            , onpComment          = {$tbl_onlinebank_comment}
            , onpStatus           = '{$onpStatus}'
            , onpCreatedAt        = {$tbl_onlinebank_date}
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);
            $onpid = Yii::$app->db->getLastInsertID();

            //create wallet transaction
            $qry =<<<SQL
  INSERT INTO tbl_AAA_WalletTransaction
          SET wtrUUID             = UUID()
            , wtrWalletID		      = {$walid}
            , wtrVoucherID		    = {$voucherid}
            , wtrOnlinePaymentID  = {$onpid}
            , wtrAmount			      = {$tbl_onlinebank_price}
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //update wallet
            $qry =<<<SQL
  UPDATE tbl_AAA_Wallet
     SET walRemainedAmount = walRemainedAmount + {$tbl_onlinebank_price}
   WHERE walID = {$walid}
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //phase 2: membership
            if ($tbl_onlinebank_status == 1) { //OK
              //create basket voucher
              $vchItems = Json::encode([
                [
                  'key'       => Uuid::uuid4()->toString(),
                  'userid'    => $userid,
                  'service'   => 'mha',
                  // 'slbkey'    => 'mbrshp',
                  'slbid'     => $membershipID,
                  'desc'      => 'حق عضویت تا (نامعلوم)',
                  'qty'       => 1,
                  'maxqty'    => 1,
                  'unitprice' => $tbl_onlinebank_price,
                ],
              ]);

              $qry =<<<SQL
  INSERT INTO tbl_AAA_Voucher
          SET vchUUID         = UUID()
            , vchOwnerUserID  = {$userid}
            , vchType         = '{$fnGetConst(enuVoucherType::Basket)}'
            , vchAmount       = {$tbl_onlinebank_price}
            , vchTotalAmount  = {$tbl_onlinebank_price}
            , vchPaidByWallet = {$tbl_onlinebank_price}
            , vchTotalPaid    = {$tbl_onlinebank_price}
            , vchItems        = '{$vchItems}'
            , vchStatus       = '{$fnGetConst(enuVoucherStatus::Finished)}'
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);
              $voucherid = Yii::$app->db->getLastInsertID();

              //create wallet transaction
              $qry =<<<SQL
  INSERT INTO tbl_AAA_WalletTransaction
          SET wtrUUID       = UUID()
            , wtrWalletID		= {$walid}
            , wtrVoucherID	= {$voucherid}
            , wtrAmount			= (-1) * {$tbl_onlinebank_price}
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //update wallet
              $qry =<<<SQL
  UPDATE tbl_AAA_Wallet
     SET walRemainedAmount = walRemainedAmount - {$tbl_onlinebank_price}
   WHERE walID = {$walid}
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);













              //member membership
              $qry =<<<SQL
  INSERT INTO tbl_MHA_MemberMembership
          SET mbrshpUUID         = UUID()
            , mbrshpMemberID     = {$userid}
            , mbrshpMembershipID = {$membershipID}
            , mbrshpVoucherID    = {$voucherid}
            , mbrshpStartDate    = {$tbl_onlinebank_expire}
            , mbrshpEndDate      = {$tbl_onlinebank_expire}
            , mbrshpStatus       = '{$fnGetConst(enuMemberMembershipStatus::Paid)}'
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);

              //phase 3: member
              $qry =<<<SQL
  UPDATE tbl_MHA_Member
     SET mbrAcceptedAt = DATE_ADD({$tbl_onlinebank_expire}, INTERVAL -1 YEAR)
   WHERE mbrUserID = {$userid}
     AND (mbrAcceptedAt IS NULL
      OR mbrAcceptedAt > DATE_ADD({$tbl_onlinebank_expire}, INTERVAL -1 YEAR)
         )
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);

            } //if ($tbl_onlinebank_status == 1) { //OK

            //phase 4: log
            //log to tbl_convert
            $qry =<<<SQL
  INSERT INTO tbl_convert(tableName, lastID, at)
        VALUES ('{$convertKey}', $lastID, NOW())
            ON DUPLICATE KEY UPDATE lastID={$lastID}, at=NOW();
SQL;
            $this->queryExecute($qry, __FUNCTION__, __LINE__);

            //remove lastID from tbl_convert.info
            if ($fnRemoveFromErrorIDs($lastID)) {
              $this->log("  REMOVE '{$lastID}' FROM ERRORS");

              $qry =<<<SQL
  UPDATE tbl_convert
     SET info = IF (info IS NULL OR LENGTH(info)=0,
           NULL,
           IF (LOCATE(',{$lastID},', CONCAT(',', info, ',')) >= 1,
             TRIM(BOTH ',' FROM REPLACE(CONCAT(',', info, ','), ',{$lastID},', ',')),
             info
           )
         )
       , at=NOW()
   WHERE tableName = '{$convertKey}';
SQL;
              $this->queryExecute($qry, __FUNCTION__, __LINE__);
            }

            //commit
            $transaction->commit();

          } catch (\Throwable $exp) {
            $transaction->rollBack();
            throw $exp;
          }


*/


        } catch (\Throwable $exp) {
          echo "** ERROR: ID: {$lastID} **\n";
          throw $exp;
        }
      } //foreach ($rows as $row)

    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_create_default_password_for_members(&$convertTableData)
  {
    $this->log("create default password for members:");

    $convertKey = 'v2.create_default_password_for_members';
    $lastID = $convertTableData[$convertKey]['lastID'] ?? 0;

    //-----------------
    $fnPutData = function($values, $lastID) use($convertKey) {
      $this->putData('tbl_AAA_User', [
        'usrID',
        'usrPasswordHash',
        'usrPasswordCreatedAt',
        'usrMustChangePassword',
      ], $values, $lastID, $convertKey, [
        'usrPasswordHash',
        'usrPasswordCreatedAt',
        'usrMustChangePassword',
      ]);
    };

    $saveCount = 8;
    $fetchCount = $saveCount;
    $loopCount = 0;
    $maxLoopCount = 3;
    while (true) {
      ++$loopCount;

      if (($maxLoopCount > 0) && ($loopCount > $maxLoopCount))
        break;

      $qry =<<<SQL
      SELECT usrID
           , mbrUserID
           , mbrRegisterCode

        FROM tbl_MHA_Member

  INNER JOIN tbl_AAA_User
          ON tbl_AAA_User.usrID = tbl_MHA_Member.mbrUserID

       WHERE tbl_MHA_Member.mbrUserID > {$lastID}
         AND tbl_AAA_User.usrPasswordHash IS NULL
         AND tbl_MHA_Member.mbrRegisterCode IS NOT NULL

    ORDER BY tbl_MHA_Member.mbrUserID

       LIMIT {$fetchCount}
SQL;

      $this->log("  fetching data from ({$lastID})+1...");
      $rows = $this->queryAll($qry, __FUNCTION__, __LINE__);

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['mbrUserID']);

        //------------
        try {
          $this->log("  >{$lastID}");

          $mbrRegisterCode = trim($row['mbrRegisterCode']);

          $usrPasswordHash = Yii::$app->security->generatePasswordHash($mbrRegisterCode);
          $this->log("    +psw");

          //user must change password at first login
          $values[$lastID] = implode(',', [
            /* usrID                 */ $lastID,
            /* usrPasswordHash       */ "'{$usrPasswordHash}'",
            /* usrPasswordCreatedAt  */ 'NOW()',
            /* usrMustChangePassword */ 1
          ]);
        } catch (\Throwable $exp) {
          echo "** ERROR: ID: {$lastID} **\n";
          throw $exp;
        } catch (\Exception $exp) {
          echo "** ERROR: ID: {$lastID} **\n";
          throw $exp;
        }

        if (count($values) >= $saveCount) {
          $fnPutData($values, $lastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $lastID);
        $values = [];
      }
    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $lastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $lastID
      ];

    $this->log("  converted to '{$lastID}'");
  }

  public function convert_profile_to_Usr_other_1(&$convertTableData)
  {
    $this->log("profile_to_Usr_other_1:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->user.other(1)';

    list ($queryLastID, $errorids) = $this->initializeWorker($convertTableData, $convertKey);

    $processedErrorIds = [];

    //-----------------
    $fnPutData = function(array $values, $lastID) use($convertKey, &$errorids, &$processedErrorIds) {
      $this->putData('tbl_AAA_User', [
        'usrID',
        'usrEducationLevel',
        'usrFieldOfStudy',
        'usrYearOfGraduation',
        'usrEducationPlace',
        'usrMilitaryStatus',
        'usrMaritalStatus',
      ], $values, $lastID, $convertKey, [
        'usrEducationLevel',
        'usrFieldOfStudy',
        'usrYearOfGraduation',
        'usrEducationPlace',
        'usrMilitaryStatus',
        'usrMaritalStatus',
      ]);

      $this->fnUnLogErrorFromConvertTable(array_keys($values), $convertKey, $errorids, $processedErrorIds);
    };

    $mapEducationLevel = [
      'سیکل'          => enuUserEducationLevel::UnderDiploma,
      'دیپلم'         => enuUserEducationLevel::Diploma,
      'دانشجو'        => enuUserEducationLevel::UniversityStudent,
      'فوق دیپلم'     => enuUserEducationLevel::Associate,
      'کاردانی'       => enuUserEducationLevel::Associate,
      'لیسانس'        => enuUserEducationLevel::Bachelor,
      'کارشناسی'      => enuUserEducationLevel::Bachelor,
      'فوق لیسانس'    => enuUserEducationLevel::Master,
      'کارشناسی ارشد' => enuUserEducationLevel::Master,
      'دکتری ( PHD )' => enuUserEducationLevel::PhD,
      'سایر'          => null,
    ];

    $mapMilitaryStatus = [
      'پایان خدمت' => enuUserMilitaryStatus::Done,
      'حین خدمت'   => enuUserMilitaryStatus::InTheArmy,
      'مشمول'      => enuUserMilitaryStatus::SubjectToService,
      'معاف'       => enuUserMilitaryStatus::ExemptedFromService,
    ];

    $mapMaritalStatus = [
      'متاهل' => enuUserMaritalStatus::Married,
      'مجرد' => enuUserMaritalStatus::NotMarried,
    ];

    $fnFromMapIfNotNull = function($value, $map, $nullValue = null) {
      if (empty($value))
        return $nullValue;

      if (empty($map[$value]))
        throw new \Exception("{$value} not found in map");

      return $map[$value];
    };

    $values = [];
    $fetchCount = 1000;
    $saveCount = 100;
    $loopCount = 0;

    while (true) {
      ++$loopCount;

      // if ($loopCount > 1)
      //   break;

      //-- create where and newFetchCount -------------------------------
      $thisLoopErrorIDs = array_filter($errorids, function($var) use($queryLastID) {
        return ($var <= $queryLastID);
      });

      $erroridsCount = count($thisLoopErrorIDs);
      $newFetchCount = $fetchCount;
      if ($erroridsCount > $newFetchCount)
        $newFetchCount += $erroridsCount;

      $where = "(tbl_profile.tbl_profile_id > {$queryLastID} AND tbl_profile.tbl_profile_id != 4)";
      if (empty($thisLoopErrorIDs) == false) {
        $where = '(' . $where . "\nOR tbl_profile.tbl_profile_id IN (" . implode(',', $thisLoopErrorIDs) . ")\n)";
      }
      $where .= "\n";
      if (empty($processedErrorIds) == false) {
        $where .= "AND tbl_profile.tbl_profile_id NOT IN (" . implode(',', $processedErrorIds) . ")\n";
      }

      // var_dump(['thisLoopErrorIDs' => $thisLoopErrorIDs, 'where' => $where]);

      $qry =<<<SQL
      SELECT tbl_profile.*
           , tbl_otherinfo.*

        FROM tbl_profile

  INNER JOIN tbl_otherinfo
          ON tbl_otherinfo.tbl_otherinfo_systemcode = tbl_profile.tbl_profile_systemcode

       WHERE {$where}

    ORDER BY tbl_profile.tbl_profile_id

       LIMIT {$newFetchCount}
SQL;

      $this->log("  fetching data from ({$queryLastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_profile_id']);

        if ($lastID > $queryLastID)
          $queryLastID = $lastID;

        //------------
        try {
          // $this->log("  >{$lastID}");

          $Education        = $fnFromMapIfNotNull($this->nullIfEmpty($row['tbl_otherinfo_fld5'], null), $mapEducationLevel);
          $FieldOfStudy     = $this->nullIfEmpty($row['tbl_otherinfo_fld6'], null);
          $YearOfGraduation = $this->nullIfEmpty($row['tbl_otherinfo_fld7'], null);
          $EducationPlace   = $this->nullIfEmpty($row['tbl_otherinfo_fld8'], null);
          $MilitaryStatus   = $fnFromMapIfNotNull($this->nullIfEmpty($row['tbl_otherinfo_fld9'], null), $mapMilitaryStatus);
          // $tbl_otherinfo_fld10 = $this->nullIfEmpty($row['tbl_otherinfo_fld10'], null);
          $MaritalStatus   = $fnFromMapIfNotNull($this->nullIfEmpty($row['tbl_profile_fld11'], null), $mapMaritalStatus);

          if (empty($YearOfGraduation) == false) {
            $YearOfGraduation = StringHelper::fixPersianCharacters($YearOfGraduation);
            if (is_numeric($YearOfGraduation) == false)
              throw new \Exception("{$YearOfGraduation} is not a number");
          }

          $values[$lastID] = implode(',', [
            /* usrID               */ $lastID + 100,
            /* usrEducationLevel   */ $this->quotedString($Education),
            /* usrFieldOfStudy     */ $this->quotedString($FieldOfStudy),
            /* usrYearOfGraduation */ $this->nullIfEmpty($YearOfGraduation),
            /* usrEducationPlace   */ $this->quotedString($EducationPlace),
            /* usrMilitaryStatus   */ $this->quotedString($MilitaryStatus),
            /* usrMaritalStatus    */ $this->quotedString($MaritalStatus),
          ]);

        } catch (\Throwable $exp) {
          $this->fnLogErrorToConvertTable($lastID, $exp->getMessage(), $convertKey, $errorids, $processedErrorIds);
          // echo "** ERROR: ID: {$lastID} **\n";
          // throw $exp;
        }

        if (count($values) >= $saveCount) {
          $fnPutData($values, $queryLastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $queryLastID);
        $values = [];
      }
    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $queryLastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $queryLastID
      ];

    $this->log("  converted to '{$queryLastID}'");
  }

  public function convert_profile_to_Mbr_other_1(&$convertTableData)
  {
    $this->log("profile_to_Mbr_other_1:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->member.other(1)';

    list ($queryLastID, $errorids) = $this->initializeWorker($convertTableData, $convertKey);

    $processedErrorIds = [];

    //-----------------
    $fnPutData = function(array $values, $lastID) use($convertKey, &$errorids, &$processedErrorIds) {
      $this->putData('tbl_MHA_Member', [
        'mbrUserID',
        'mbrInstrumentID',
        'mbrSingID',
        'mbrResearchID',
        'mbrJob',
        'mbrArtDegree',
        'mbrHonarCreditCode',
      ], $values, $lastID, $convertKey, [
        'mbrInstrumentID',
        'mbrSingID',
        'mbrResearchID',
        'mbrJob',
        'mbrArtDegree',
        'mbrHonarCreditCode',
      ]);

      $this->fnUnLogErrorFromConvertTable(array_keys($values), $convertKey, $errorids, $processedErrorIds);
    };

    $mapInstruments = ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Instrument])->asArray()->all(), 'bdfName', 'bdfID');

    $mapSings = ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Sing])->asArray()->all(), 'bdfName', 'bdfID');

    $mapResearches = ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Research])->asArray()->all(), 'bdfName', 'bdfID');

    $mapArtDegree = [
      'درجه اول'    => 1,
      'درجه دوم'    => 2,
      'درجه سوم'    => 3,
      'درجه چهارم'  => 4,
      'درجه پنجم'   => 5,
    ];

    $fnFromMapIfNotNull = function($value, $map, $nullValue = null) {
      if (empty($value))
        return $nullValue;

      if (empty($map[$value]))
        throw new \Exception("{$value} not found in map");

      return $map[$value];
    };

    $values = [];
    $fetchCount = 1000;
    $saveCount = 100;
    $loopCount = 0;

    while (true) {
      ++$loopCount;

      // if ($loopCount > 1)
      //   break;

      //-- create where and newFetchCount -------------------------------
      $thisLoopErrorIDs = array_filter($errorids, function($var) use($queryLastID) {
        return ($var <= $queryLastID);
      });

      $erroridsCount = count($thisLoopErrorIDs);
      $newFetchCount = $fetchCount;
      if ($erroridsCount > $newFetchCount)
        $newFetchCount += $erroridsCount;

      $where = "(tbl_profile.tbl_profile_id > {$queryLastID} AND tbl_profile.tbl_profile_id != 4)";
      if (empty($thisLoopErrorIDs) == false) {
        $where = '(' . $where . "\nOR tbl_profile.tbl_profile_id IN (" . implode(',', $thisLoopErrorIDs) . ")\n)";
      }
      $where .= "\n";
      if (empty($processedErrorIds) == false) {
        $where .= "AND tbl_profile.tbl_profile_id NOT IN (" . implode(',', $processedErrorIds) . ")\n";
      }

      // var_dump(['thisLoopErrorIDs' => $thisLoopErrorIDs, 'where' => $where]);

      $qry =<<<SQL
      SELECT tbl_profile.*
           , tbl_otherinfo.*

        FROM tbl_profile

  INNER JOIN tbl_otherinfo
          ON tbl_otherinfo.tbl_otherinfo_systemcode = tbl_profile.tbl_profile_systemcode

       WHERE {$where}

    ORDER BY tbl_profile.tbl_profile_id

       LIMIT {$newFetchCount}
SQL;

      $this->log("  fetching data from ({$queryLastID})+1...");
      $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

      if (empty($rows)) {
        if ($loopCount == 1) {
          $this->log("  nothing to do");
          return;
        }
        //else:
        break;
      }

      $this->log("  source data fetched");

      foreach ($rows as $row) {
        $lastID = trim($row['tbl_profile_id']);

        if ($lastID > $queryLastID)
          $queryLastID = $lastID;

        //------------
        try {
          // $this->log("  >{$lastID}");

          $InstrumentID = $this->nullIfEmpty($row['tbl_profile_fld9'], null);
          $InstrumentID = (empty($InstrumentID) ? 'NULL' : $fnFromMapIfNotNull(StringHelper::fixPersianCharacters($InstrumentID), $mapInstruments));

          $SingID = $this->nullIfEmpty($row['tbl_profile_fld20'], null);
          $SingID = (empty($SingID) ? 'NULL' : $fnFromMapIfNotNull(StringHelper::fixPersianCharacters($SingID), $mapSings));

          $ResearchID = $this->nullIfEmpty($row['tbl_profile_fld21'], null);
          $ResearchID = (empty($ResearchID) ? 'NULL' : $fnFromMapIfNotNull(StringHelper::fixPersianCharacters($ResearchID), $mapResearches));

          $Job = $this->quotedString($row['tbl_profile_fld14']);

          $ArtDegree = 'NULL';
          if ($this->nullIfEmpty($row['tbl_otherinfo_fldn39'], null) == 'دارد') {
            $ArtDegree = $this->nullIfEmpty($row['tbl_otherinfo_fldn38'], null);
            if (empty($ArtDegree))
              $ArtDegree = 'NULL';
            else
              $ArtDegree = $mapArtDegree[$ArtDegree];
          }

          $HonarCreditCode = 'NULL';
          if ($this->nullIfEmpty($row['tbl_otherinfo_fldn40'], null) != null) {
            $HonarCreditCode = $this->nullIfEmpty($row['tbl_otherinfo_fldn41'], null);
            if (empty($HonarCreditCode))
              $HonarCreditCode = 'NULL';
            else
              $HonarCreditCode = $this->quotedString($HonarCreditCode);
          }

          $values[$lastID] = implode(',', [
            /* mbrUserID          */ $lastID + 100,
            /* mbrInstrumentID    */ $InstrumentID,
            /* mbrSingID          */ $SingID,
            /* mbrResearchID      */ $ResearchID,
            /* mbrJob             */ $Job,
            /* mbrArtDegree       */ $ArtDegree,
            /* mbrHonarCreditCode */ $HonarCreditCode,
          ]);

        } catch (\Throwable $exp) {
          $this->fnLogErrorToConvertTable($lastID, $exp->getMessage(), $convertKey, $errorids, $processedErrorIds);
          // echo "** ERROR: ID: {$lastID} **\n";
          // throw $exp;
        }

        if (count($values) >= $saveCount) {
          $fnPutData($values, $queryLastID);
          $values = [];
        }
      } //foreach ($rows as $row)

      if (empty($values) == false) {
        $fnPutData($values, $queryLastID);
        $values = [];
      }
    } //while (true)

    if (isset($convertTableData[$convertKey]))
      $convertTableData[$convertKey]['lastID'] = $queryLastID;
    else
      $convertTableData[$convertKey] = [
        'lastID' => $queryLastID
      ];

    $this->log("  converted to '{$queryLastID}'");
  }

  public function copylostimages()
  {
/*
    $files = [
      "userprofile_1613821716.jpg" => "/home2/iranhmus/domains/api.iranhmusic.ir/public_html/tmp/upload/user/69/1D/99/691D9952/10499/user/userprofile_1613821716.jpg",
    ];

    $tmpPathUser = Yii::$app->params['convert_source_files_path_user'];
    $tmpPathDocument = Yii::$app->params['convert_source_files_path_document'];

    foreach ($files as $k => $v) {
      if (strpos($v, '/document/') === false) {
        $org = $tmpPathUser . $k;
      } else {
        $org = $tmpPathDocument . $k;
      }

      copy($org, $v);
    }
*/
  }

  public function convert_update_members_expiredate(&$convertTableData)
  {
    $this->log("update members expire date:");

    $qry =<<<SQL
    UPDATE tbl_MHA_Member mbr
INNER JOIN (
    SELECT uas.uasActorID
         , MAX(uas.uasValidToDate) AS dtexpire
      FROM tbl_MHA_Accounting_UserAsset uas
INNER JOIN tbl_MHA_Accounting_Saleable slb
        ON slb.slbID = uas.uasSaleableID
INNER JOIN tbl_MHA_Accounting_Product prd
        ON prd.prdID = slb.slbProductID
     WHERE prd.prdMhaType = 'M'
  GROUP BY uas.uasActorID
           ) t1
        ON t1.uasActorID = mbr.mbrUserID
       SET mbr.mbrExpireDate = t1.dtexpire
     WHERE mbr.mbrExpireDate IS NULL
        OR mbr.mbrExpireDate < t1.dtexpire
SQL;

    $rowsCount = $this->queryExecute($qry, __FUNCTION__, __LINE__);
    $this->log("update members expire date: {$rowsCount}");
  }

  public function convert_profile_to_Mbr_expiredate(&$convertTableData)
  {
    $this->log("profile_to_Mbr_expiredate:");

    $oldcrmdbv2 = Yii::$app->oldcrmdbv2;

    $convertKey = 'v2.tbl_profile->member.expiredate';

    list ($queryLastID, $errorids) = $this->initializeWorker($convertTableData, $convertKey);

    $processedErrorIds = [];

    //-----------------
    $fnPutData = function(array $values, $lastID) use($convertKey, &$errorids, &$processedErrorIds) {
      $this->putData('tbl_MHA_Member', [
        'mbrUserID',
        'mbrExpireDate_convert',
      ], $values, $lastID, $convertKey, [
        'mbrExpireDate_convert',
      ]);

      $this->fnUnLogErrorFromConvertTable(array_keys($values), $convertKey, $errorids, $processedErrorIds);
    };

    try {
      //phase 1: add column
      $this->log('  check mbrExpireDate_convert column');
      $qry =<<<SQL
  SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE()
     AND TABLE_NAME   = 'tbl_MHA_Member'
     AND COLUMN_NAME  = 'mbrExpireDate_convert';
SQL;
      $cnt1 = $this->queryOne($qry, __FUNCTION__, __LINE__);
      if (empty($cnt1)) {
        $this->log('  creating mbrExpireDate_convert column');

        $qry =<<<SQL
ALTER TABLE `tbl_MHA_Member`
  ADD COLUMN `mbrExpireDate_convert` VARCHAR(256) NULL DEFAULT NULL;
SQL;
        if ($this->queryExecute($qry, __FUNCTION__, __LINE__) == 0)
          $this->log('  error creating mbrExpireDate_convert column');
        else
          $this->log('  created mbrExpireDate_convert column');
      }

      //phase 2: copy old expire dates to tbl_MHA_Member
      $values = [];
      $fetchCount = 1000;
      $saveCount = 100;
      $loopCount = 0;
      while (true) {
        ++$loopCount;

        // if ($loopCount > 1)
        //   break;

        //-- create where and newFetchCount -------------------------------
        $thisLoopErrorIDs = array_filter($errorids, function($var) use($queryLastID) {
          return ($var <= $queryLastID);
        });

        $erroridsCount = count($thisLoopErrorIDs);
        $newFetchCount = $fetchCount;
        if ($erroridsCount > $newFetchCount)
          $newFetchCount += $erroridsCount;

        $where = "(tbl_profile.tbl_profile_id > {$queryLastID} AND tbl_profile.tbl_profile_id != 4)";
        if (empty($thisLoopErrorIDs) == false) {
          $where = '(' . $where . "\nOR tbl_profile.tbl_profile_id IN (" . implode(',', $thisLoopErrorIDs) . ")\n)";
        }
        $where .= "\n";
        if (empty($processedErrorIds) == false) {
          $where .= "AND tbl_profile.tbl_profile_id NOT IN (" . implode(',', $processedErrorIds) . ")\n";
        }

        // var_dump(['thisLoopErrorIDs' => $thisLoopErrorIDs, 'where' => $where]);

        $qry =<<<SQL
  SELECT tbl_profile.*

    FROM tbl_profile

    WHERE {$where}
      AND tbl_profile_expiredate IS NOT NULL
      AND tbl_profile_expiredate != ''

ORDER BY tbl_profile.tbl_profile_id

    LIMIT {$newFetchCount}
SQL;

        $this->log("  fetching data from ({$queryLastID})+1...");
        $rows = $oldcrmdbv2->createCommand($qry)->queryAll();

        if (empty($rows)) {
          if ($loopCount == 1) {
            $this->log("  nothing to do");
            return;
          }
          //else:
          break;
        }

        $this->log("  source data fetched");

        foreach ($rows as $row) {
          $lastID = trim($row['tbl_profile_id']);

          if ($lastID > $queryLastID)
            $queryLastID = $lastID;

          //------------
          try {
            // $this->log("  >{$lastID}");

            $tbl_profile_expiredate_jalali = trim($row['tbl_profile_expiredate'] ?? '');
            $tbl_profile_expiredate = $this->jalaliToMiladi($row['tbl_profile_expiredate']);

            $values[$lastID] = implode(',', [
              /* mbrUserID             */ $lastID + 100,
              /* mbrExpireDate_convert */ $tbl_profile_expiredate,
            ]);

          } catch (\Throwable $exp) {
            $this->fnLogErrorToConvertTable($lastID, $exp->getMessage(), $convertKey, $errorids, $processedErrorIds);
            // echo "** ERROR: ID: {$lastID} **\n";
            // throw $exp;
          }

          if (count($values) >= $saveCount) {
            $fnPutData($values, $queryLastID);
            $values = [];
          }
        } //foreach ($rows as $row)

        if (empty($values) == false) {
          $fnPutData($values, $queryLastID);
          $values = [];
        }
      } //while (true)

      if (isset($convertTableData[$convertKey]))
        $convertTableData[$convertKey]['lastID'] = $queryLastID;
      else
        $convertTableData[$convertKey] = [
          'lastID' => $queryLastID
        ];

      $this->log("  converted to '{$queryLastID}'");

      //phase 3: convert mbrExpireDate_convert to mbrExpireDate if can
      $qry =<<<SQL
 UPDATE tbl_MHA_Member
    SET mbrExpireDate = mbrExpireDate_convert
  WHERE mbrExpireDate_convert IS NOT NULL
    AND (
        mbrExpireDate IS NULL
     OR mbrExpireDate < mbrExpireDate_convert
        )
SQL;

      $rowsCount = $this->queryExecute($qry, __FUNCTION__, __LINE__);
      $this->log("update members expire date: {$rowsCount}");

    } catch (\Throwable $th) {
      $this->log($th->getMessage(), 'ERROR');

    } finally {
      $qry =<<<SQL
ALTER TABLE `tbl_MHA_Member`
  DROP COLUMN `mbrExpireDate_convert`;
SQL;
      $this->queryExecute($qry, __FUNCTION__, __LINE__);
    }
  }

}
