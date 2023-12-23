<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m231219_151044_mha_convert_uasVoucherItemInfo extends Migration
{
  public function safeUp()
  {
    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
  CHANGE COLUMN `uasVoucherItemInfo` `OLD_uasVoucherItemInfo` JSON NULL DEFAULT NULL AFTER `uasVoucherID`;
SQLSTR
    );

    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
  ADD COLUMN `uasVoucherItemInfo` JSON NULL DEFAULT NULL AFTER `OLD_uasVoucherItemInfo`;
SQLSTR
    );
    $this->alterColumn('tbl_MHA_Accounting_UserAsset', 'uasVoucherItemInfo', $this->json());

    $this->execute(<<<SQLSTR
UPDATE tbl_MHA_Accounting_UserAsset
  INNER JOIN (
      SELECT uasID
           , tmp1.discount
           , JSON_REMOVE(JSON_OBJECT(
               "service",       tmp1.service
             , "key",           tmp1.`key`
--             , "slbID",         tmp1.slbID
              "desc",          tmp1.`desc`
--             , "qty",           tmp1.qty
--             , "unit",          tmp1.unit
--             , "prdType",       tmp1.prdType
             , "params",        tmp1.params
             , "unitPrice",     tmp1.unitPrice
--             , "maxQty",        tmp1.maxQty
--             , "qtyStep",       tmp1.qtyStep
--             , "discount",      IFNULL(tmp1.discount, 0)
             , "subTotal",      tmp1.qty * tmp1.unitPrice
             , "afterDiscount", tmp1.qty * tmp1.unitPrice
             , "totalPrice",    tmp1.qty * tmp1.unitPrice
           )
--           , CASE WHEN tmp1.service   IS NULL OR CONCAT(tmp1.service  , '') IN ('', '0') THEN '$.service'   ELSE '$.dummy' END
--           , CASE WHEN tmp1.key       IS NULL OR CONCAT(tmp1.`key`    , '') IN ('', '0') THEN '$.key'       ELSE '$.dummy' END
--           , CASE WHEN tmp1.slbID     IS NULL OR CONCAT(tmp1.slbID    , '') IN ('', '0') THEN '$.slbID'     ELSE '$.dummy' END
           , CASE WHEN tmp1.desc      IS NULL OR CONCAT(tmp1.`desc`   , '') IN ('', '0') THEN '$.desc'      ELSE '$.dummy' END
--           , CASE WHEN tmp1.qty       IS NULL OR CONCAT(tmp1.qty      , '') IN ('', '0') THEN '$.qty'       ELSE '$.dummy' END
--           , CASE WHEN tmp1.unit      IS NULL OR CONCAT(tmp1.unit     , '') IN ('', '0') THEN '$.unit'      ELSE '$.dummy' END
--           , CASE WHEN tmp1.prdType   IS NULL OR CONCAT(tmp1.prdType  , '') IN ('', '0') THEN '$.prdType'   ELSE '$.dummy' END
           , CASE WHEN tmp1.params    IS NULL OR CONCAT(tmp1.params   , '') IN ('', '0') THEN '$.params'    ELSE '$.dummy' END
           , CASE WHEN tmp1.unitPrice IS NULL OR CONCAT(tmp1.unitPrice, '') IN ('', '0') THEN '$.unitPrice' ELSE '$.dummy' END
--           , CASE WHEN tmp1.maxQty    IS NULL OR CONCAT(tmp1.maxQty   , '') IN ('', '0') THEN '$.maxQty'    ELSE '$.dummy' END
--           , CASE WHEN tmp1.qtyStep   IS NULL OR CONCAT(tmp1.qtyStep  , '') IN ('', '0') THEN '$.qtyStep'   ELSE '$.dummy' END
--           , CASE WHEN tmp1.discount  IS NULL OR CONCAT(tmp1.discount , '') IN ('', '0') THEN '$.discount'  ELSE '$.dummy' END
             ) AS NEW_uasVoucherItemInfo
        FROM (
      SELECT uasID
           ,              JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].service')    AS service
           ,              JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].key')        AS `key`
           , JSON_UNQUOTE(JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].slbid'))     AS slbID
           ,              JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].desc')       AS `desc`
           , JSON_UNQUOTE(JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].qty'))       AS qty
           ,              JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].unit')       AS unit
           ,              JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].prdtype')    AS prdType
           ,              JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].slbinfo')    AS params
           , JSON_UNQUOTE(JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].unitprice')) AS unitPrice
           , JSON_UNQUOTE(JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].maxqty'))    AS maxQty
           , JSON_UNQUOTE(JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].qtystep'))   AS qtyStep
           , JSON_UNQUOTE(JSON_EXTRACT(OLD_uasVoucherItemInfo, '$[0].discount'))  AS discount
        FROM tbl_MHA_Accounting_UserAsset
       WHERE JSON_LENGTH(IFNULL(OLD_uasVoucherItemInfo, '[]')) > 0
             ) AS tmp1
             ) AS tmpJson
          ON tmpJson.uasID = tbl_MHA_Accounting_UserAsset.uasID
         SET uasVoucherItemInfo = tmpJson.NEW_uasVoucherItemInfo
           , uasDiscountAmount = CASE WHEN tmpJson.discount IS NULL OR CONCAT(tmpJson.discount, '') IN ('', '0') THEN NULL ELSE tmpJson.discount END
;
SQLSTR
    );

  }

  public function safeDown()
  {
    echo "m231219_151044_mha_convert_uasVoucherItemInfo cannot be reverted.\n";
    return false;
  }

}
