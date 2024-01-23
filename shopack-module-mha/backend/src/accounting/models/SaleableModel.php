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

  public static function ProcessVoucherItem($voucherID, $userid, $voucherItemdata)
  {
    $slbid = $voucherItemdata['slbid'];
    $saleableModel = SaleableModel::find()->andWhere(['slbid' => $slbid])
      ->joinWith('product')
      ->one();

    if ($saleableModel == null)
      throw new UnprocessableEntityHttpException("Invalid saleable id ({$slbid})");

    //check existance
    $key = $voucherItemdata['key'];
    $userAssetModel = UserAssetModel::find()->andWhere(['uasUUID' => $key])->one();
    if ($userAssetModel != null)
      return true; //already exists

    //1: save user asset
    $service		= $voucherItemdata['service'];
    // $slbkey			= $voucherItemdata['slbkey'];
    $desc				= $voucherItemdata['desc'];
    $qty				= $voucherItemdata['qty'];
    $unitprice	= $voucherItemdata['unitprice'];
    //additives
    //discount
    //tax
    //totalprice
    $startDate	= $voucherItemdata['slbinfo']['startDate'] ?? null;
    $endDate		= $voucherItemdata['slbinfo']['endDate'] ?? null;

    $userAssetModel = new UserAssetModel;
    $userAssetModel->uasUUID						= $key;
    $userAssetModel->uasActorID         = $userid;
    $userAssetModel->uasSaleableID      = $slbid;
    $userAssetModel->uasQty             = $qty;
    $userAssetModel->uasVoucherID       = $voucherID;
    $userAssetModel->uasVoucherItemInfo = $voucherItemdata;
    // $userAssetModel->uasDiscountID        =
    // $userAssetModel->uasDiscountAmount  =
    // $userAssetModel->uasPrefered        =
    $userAssetModel->uasValidFromDate   = $startDate;
    $userAssetModel->uasValidToDate     = $endDate;
    // $userAssetModel->uasValidFromHour   =
    // $userAssetModel->uasValidToHour     =
    // $userAssetModel->uasDurationMinutes =
    // $userAssetModel->uasBreakedAt       =

    if ($saleableModel->product->prdMhaType == enuMhaProductType::Membership) {
      $userAssetModel->uasStatus = enuUserAssetStatus::Active;
    } else if ($saleableModel->product->prdMhaType == enuMhaProductType::MembershipCard) {
      // $userAssetModel->uasStatus = wait for card print
    } else {
      throw new UnprocessableEntityHttpException("Invalid mha product type ({$saleableModel->product->prdMhaType})");
    }

    if ($userAssetModel->save() == false)
      throw new ServerErrorHttpException('It is not possible to create user asset');

    //2: ?
    if ($saleableModel->product->prdMhaType == enuMhaProductType::Membership) {
      self::ProcessVoucherItem_Membership($userAssetModel, $voucherItemdata);
    } else if ($saleableModel->product->prdMhaType == enuMhaProductType::MembershipCard) {
      self::ProcessVoucherItem_MembershipCard($userAssetModel, $voucherItemdata);
    }
  }

  public static function ProcessVoucherItem_Membership($userAssetModel, $voucherItemdata)
  {
    $memberModel = MemberModel::find()
      ->andWhere(['mbrUserID' => $userAssetModel->uasActorID])
      ->one();

    $memberModel->mbrExpireDate = $userAssetModel->uasValidToDate;

    $memberModel->save();
  }

  public static function ProcessVoucherItem_MembershipCard($userAssetModel, $voucherItemdata)
  {
  }

}
