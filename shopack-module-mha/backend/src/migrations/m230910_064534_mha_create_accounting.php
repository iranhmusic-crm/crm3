<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230910_064534_mha_create_accounting extends Migration
{
  public function safeUp()
  {




    throw new \Exception('not completed yet!');



    /*
    INSERT INTO tbl_MHA_Accounting_Unit(untID, untUUID, untName, untI18NData)
    VALUES
        (1, UUID(), 'سال', '{"en":{"untName":"Year"}}'),
        (2, UUID(), 'دفعه', '{"en":{"untName":"Times"}}')
    ;


trg_tbl_MHA_Accounting_Saleable_before_insert



    */


    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
CREATE TRIGGER `trg_tbl_MHA_Accounting_Saleable_before_insert` BEFORE INSERT ON `tbl_MHA_Accounting_Saleable` FOR EACH ROW BEGIN
	IF NEW.slbCode IS NULL THEN
		SET NEW.slbCode = UUID();
	END IF;

    IF NEW.slbAvailableFromDate IS NULL THEN
		SET NEW.slbAvailableFromDate = NOW();
	END IF;
END
SQLSTR
    );

  }

  public function safeDown()
  {
    echo "m230910_064534_mha_create_accounting cannot be reverted.\n";
    return false;
  }

}
