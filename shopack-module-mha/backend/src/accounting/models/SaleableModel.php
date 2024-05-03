<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;
use iranhmusic\shopack\mha\backend\accounting\models\UserAssetModel;
use shopack\base\common\accounting\enums\enuUserAssetStatus;
use iranhmusic\shopack\mha\backend\models\MemberModel;

class SaleableModel extends MhaActiveRecord
{
  use \iranhmusic\shopack\mha\common\accounting\models\SaleableModelTrait;
  use \shopack\base\backend\accounting\models\BackendSaleableModelTrait;

  public static function getCustomConditionsToValidDiscountsQuery(
    $actorID,
    $validDiscountAlias = 'dscv',
    $discountWithUsageAlias = 'tmp_dsc_with_usg'
  ) {
    $row = null;

    if (Yii::$app->user->isGuest == false) { // && Yii::$app->member->isMember) {
      $qry =<<<SQL
      SELECT  mbrUserID
           ,  GROUP_CONCAT(mbrmgpMemberGroupID) AS grps
           ,  GROUP_CONCAT(mbrknnKanoonID) AS knns
        FROM  tbl_MHA_Member mbr
   LEFT JOIN  tbl_MHA_Member_MemberGroup mbrmgp
          ON  mbrmgp.mbrmgpMemberID = mbr.mbrUserID
         AND  (mbrmgpStartAt IS NULL OR mbrmgpStartAt <= NOW())
         AND  (mbrmgpEndAt IS NULL OR mbrmgpEndAt >= NOW())
   LEFT JOIN  tbl_MHA_Member_Kanoon mbrknn
          ON  mbrknn.mbrknnMemberID = mbr.mbrUserID
         AND  mbrknnStatus = 'A'
       WHERE  mbr.mbrUserID = {$actorID}
SQL;

      $row = Yii::$app->db->createCommand($qry)->queryOne();
    }

    $qry = '';

    //dscTargetMemberGroupIDs
    $qry .= " AND  (dscTargetMemberGroupIDs IS NULL";
    if (empty($row['grps']) == false) {
      $grps = explode(',', $row['grps']);
      foreach ($grps as $grp) {
        $qry .= " OR  JSON_SEARCH(dscTargetMemberGroupIDs, 'one', {$grp}) IS NOT NULL";
      }
    }
    $qry .= ")\n";

    //dscTargetKanoonIDs
    $qry .= " AND  (dscTargetKanoonIDs IS NULL";
    if (empty($row['knns']) == false) {
      $knns = explode(',', $row['knns']);
      foreach ($knns as $knn) {
        $qry .= " OR  JSON_SEARCH(dscTargetKanoonIDs, 'one', {$knn}) IS NOT NULL";
      }
    }
    $qry .= ")\n";

    //
    return $qry;
  }

  public static function getCustomConditionsToSaleableWithComputedDiscountsQuery(
    $actorID,
    $saleableWithDiscountAlias = 'slbwcv',
    $productAlias = 'prd'
  ) {
    //dscTargetProductMhaTypes
    $qry =<<<SQL
    AND (dscTargetProductMhaTypes IS NULL
     OR JSON_SEARCH(dscTargetProductMhaTypes, 'one', {$productAlias}.prdMhaType) IS NOT NULL
        )
SQL;

    return $qry;
  }

  public static function tableName()
  {
    return '{{%MHA_Accounting_Saleable}}';
  }

  /**
   * return: new status of item
   */
  public static function ProcessVoucherItem($voucherID, $userid, $voucherItemdata)
  {
    $orderID = $voucherItemdata['orderID'];
    $key = $voucherItemdata['key'];

    $userAssetModel = UserAssetModel::find()
      ->innerJoinWith('saleable.product')
      ->andWhere(['uasID' => $orderID])
      ->andWhere(['uasUUID' => $key])
      ->one();

    if ($userAssetModel == null)
      throw new UnprocessableEntityHttpException("user asset not found");

    if ($userAssetModel->uasStatus == enuUserAssetStatus::Active)
      return true;

    if ($userAssetModel->uasStatus != enuUserAssetStatus::Draft)
        // && ($userAssetModel->uasStatus != enuUserAssetStatus::Pending))
      return false;

    //start transaction
    $transaction = Yii::$app->db->beginTransaction();

    try {
      // if ($userAssetModel->saleable->product->prdMhaType == enuMhaProductType::Membership) {
      //   $userAssetModel->uasStatus = enuUserAssetStatus::Active;

      // } else if ($userAssetModel->saleable->product->prdMhaType == enuMhaProductType::MembershipCard) {

      //   // wait for card print. after print, status must be changed form Pending to Active
      //   $userAssetModel->uasStatus = enuUserAssetStatus::Pending;

      // } else {
      //   throw new UnprocessableEntityHttpException("Invalid mha product type ({$userAssetModel->saleable->product->prdMhaType})");
      // }

      //discounts

      //id, snid, amount
      $discounts = [];

      //id => sum(amount)
      // $discountsAmounts = [];

      $systemDiscounts = $userAssetModel->uasVoucherItemInfo['systemDiscounts'] ?? null;
      if (empty($systemDiscounts) == false) {
        foreach ($systemDiscounts as $dk => $dv) {
          $discounts[] = implode(',', [
            $userAssetModel->uasActorID,
            $userAssetModel->uasID,
            $dv['id'],
            'NULL',
            $dv['amount']
          ]);

          // if (array_key_exists('_' . $dv['id'], $discountsAmounts)) {
          //   $discountsAmounts['_' . $dv['id']] += $dv['amount'];
          // } else {
          //   $discountsAmounts['_' . $dv['id']] = $dv['amount'];
          // }
        }
      }

      $couponDiscount = $userAssetModel->uasVoucherItemInfo['couponDiscount'] ?? null;
      if (empty($couponDiscount['id']) == false) {

        $discountSerialID = 'NULL';
        if (empty($couponDiscount['code']) == false) {
          $discountSerialModel = DiscountSerialModel::find()
            ->andWhere(['dscsnDiscountID' => $couponDiscount['id']])
            ->andWhere(['dscsnSN' => $couponDiscount['code']])
            ->one();

          if ($discountSerialModel != null)
            $discountSerialID = $discountSerialModel->dscsnID;
        }

        $discounts[] = implode(',', [
          $userAssetModel->uasActorID,
          $userAssetModel->uasID,
          $couponDiscount['id'],
          $discountSerialID,
          $couponDiscount['amount']
        ]);

        // if (array_key_exists('_' . $couponDiscount['id'], $discountsAmounts)) {
        //   $discountsAmounts['_' . $couponDiscount['id']] += $couponDiscount['amount'];
        // } else {
        //   $discountsAmounts['_' . $couponDiscount['id']] = $couponDiscount['amount'];
        // }
      }

      if (empty($discounts) == false) {
        $discountUsageTableName = DiscountUsageModel::tableName();
        $discounts = '(' . implode('),(', $discounts) . ')';

        $qry =<<<SQL
INSERT INTO {$discountUsageTableName} (
      dscusgUserID
    , dscusgUserAssetID
    , dscusgDiscountID
    , dscusgDiscountSerialID
    , dscusgAmount
  ) VALUES {$discounts}
SQL;
        $rows = Yii::$app->db->createCommand($qry)->execute();

        //update tbl_discount moved to trigger
      }

      //user asset
      $userAssetModel->uasValidFromDate = $userAssetModel->uasVoucherItemInfo['params']['startDate'] ?? null;
      $userAssetModel->uasValidToDate = $userAssetModel->uasVoucherItemInfo['params']['endDate'] ?? null;

      if ($userAssetModel->saleable->product->prdMhaType == enuMhaProductType::Membership) {
        self::ProcessVoucherItem_Membership($userAssetModel, $voucherItemdata);
      } else if ($userAssetModel->saleable->product->prdMhaType == enuMhaProductType::MembershipCard) {
        self::ProcessVoucherItem_MembershipCard($userAssetModel, $voucherItemdata);
      }

      if ($userAssetModel->save() == false)
        throw new ServerErrorHttpException('It is not possible to create user asset');

			//commit
			$transaction->commit();

      return true; //enuVoucherItemStatus::Processed; //$userAssetModel->uasStatus;

    } catch (\Throwable $th) {
      //rollback
      $transaction->rollBack();
      Yii::error($th, __METHOD__);
      throw $th;
    }
  }

  public static function ProcessVoucherItem_Membership(&$userAssetModel, $voucherItemdata)
  {
    $memberModel = MemberModel::find()
      ->andWhere(['mbrUserID' => $userAssetModel->uasActorID])
      ->one();
    $memberModel->mbrExpireDate = $userAssetModel->uasValidToDate;

    if ($memberModel->save() == false)
      throw new \Exception(implode(',', $memberModel->getErrorSummary(true)));

    $userAssetModel->uasStatus = enuUserAssetStatus::Active;
  }

  public static function ProcessVoucherItem_MembershipCard(&$userAssetModel, $voucherItemdata)
  {
    //wait for card print.
    //todo: after print, status must be changed form Pending to Active
    $userAssetModel->uasStatus = enuUserAssetStatus::Pending;
  }

}
