<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240131_093515_mha_convert_removedat extends Migration
{
  private function changeRemoveAtToTable($tableName, $columnPrefix)
  {
    $this->execute(<<<SQL
ALTER TABLE {$tableName}
	CHANGE COLUMN {$columnPrefix}RemovedAt {$columnPrefix}RemovedAt_OLD INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER {$columnPrefix}UpdatedBy,
	ADD COLUMN {$columnPrefix}RemovedAt DATETIME NULL AFTER {$columnPrefix}RemovedAt_OLD;
SQL
    );

    $this->execute(<<<SQL
UPDATE {$tableName}
  SET {$columnPrefix}RemovedAt = FROM_UNIXTIME({$columnPrefix}RemovedAt_OLD)
  WHERE {$columnPrefix}RemovedAt_OLD > 0;
SQL
    );

    $this->execute(<<<SQL
UPDATE {$tableName}
  SET {$columnPrefix}Status = 'A'
  WHERE {$columnPrefix}Status = 'R';
SQL
    );

    $this->execute(<<<SQL
ALTER TABLE {$tableName}
	DROP COLUMN {$columnPrefix}RemovedAt_OLD;
SQL
    );
  }

  public function safeUp()
  {



    $this->execute(<<<SQL
ALTER TABLE tbl_MHA_Kanoon
  CHANGE COLUMN knnStatus knnStatus CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable' COLLATE 'utf8mb4_unicode_ci' AFTER knnTalkerMemberID;
SQL
);
    $this->changeRemoveAtToTable('tbl_MHA_Kanoon', 'knn');



  }

  public function safeDown()
  {
    echo "m240131_093515_mha_convert_removedat cannot be reverted.\n";
    return false;
  }

}
