<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use shopack\base\backend\helpers\PrivHelper;
use shopack\base\backend\controller\BaseRestController;
use iranhmusic\shopack\mha\backend\accounting\models\MembershipForm;

// use iranhmusic\shopack\mha\backend\accounting\models\RenewViaInvoiceForm;

class MembershipController extends BaseRestController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // $behaviors[static::BEHAVIOR_AUTHENTICATOR]['except'] = [
        // ];

        return $behaviors;
    }

    public function actionOptions()
    {
        return 'options';
    }

    //called by owner
    public function actionRenewalInfo($memberID = null)
    {
        if ($memberID == null)
            $memberID = Yii::$app->user->id;
        else if (($memberID != Yii::$app->user->id)
            && (PrivHelper::hasPriv('mha/member-membership/crud', '0100') == false)
        ) {
            throw new ForbiddenHttpException('access denied');
        }

        $info = MembershipForm::getRenewalInfo($memberID);

        $startDate              = $info['startDate'];
        $endDate                = $info['endDate'];
        $years                  = $info['years'];
        $unitPrice              = $info['unitPrice'];
        $totalPrice             = $info['totalPrice'];
        $saleableModel          = $info['saleableModel'];
        $cardPrintSaleableModel = $info['cardPrintSaleableModel'];
        $printCardAmount        = $info['printCardAmount'];

        return [
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'years'           => $years,
            'unitPrice'       => $unitPrice,
            'totalPrice'      => $totalPrice,
            'saleableID'      => $saleableModel->slbID,
            'printCardAmount' => $printCardAmount,
        ];
    }

    public function actionAddToBasket()
    {
        $bodyParams = Yii::$app->request->getBodyParams();

        $base64Basketdata = $bodyParams['basketdata'] ?? [];
        $printCard = $bodyParams['printCard'] ?? null;
        $discountCode = $bodyParams['discountCode'] ?? null;

        $result = MembershipForm::addToBasket($base64Basketdata, null, $printCard, $discountCode);

        return [
            'key'            => $result[0],
            'basket'    => $result[1],
        ];
    }

    //called by operator
    public function actionRenewalInfoForInvoice(
        $memberID = null,
        $ofpID = null
    ) {
        PrivHelper::checkPriv('mha/member-membership/crud', '0100');

        $info = MembershipForm::getRenewalInfoForInvoice($memberID, $ofpID);

        $startDate                    = $info['startDate'];
        $maxYears                     = $info['maxYears'];
        $memberModel                  = $info['memberModel'];
        $offlinePaymentModel          = $info['offlinePaymentModel'];
        $membershipSaleableModels     = $info['membershipSaleableModels'];
        $membershipCardSaleableModels = $info['membershipCardSaleableModels'];

        return [
            'startDate'                    => $startDate,
            'maxYears'                     => $maxYears,
            'memberModel'                  => $memberModel,
            'offlinePaymentModel'          => $offlinePaymentModel,
            'membershipSaleableModels'     => $membershipSaleableModels,
            'membershipCardSaleableModels' => $membershipCardSaleableModels
        ];
    }

    public function actionRenewViaInvoice()
    {
        PrivHelper::checkPriv('mha/member-membership/crud', '1000');

        $bodyParams = Yii::$app->request->getBodyParams();

        $memberID                 = $bodyParams['memberID'] ?? null;
        // $ofpID                 = $bodyParams['ofpID'] ?? null;
        $years                    = $bodyParams['years'];
        $membershipSaleableID     = $bodyParams['membershipSaleableID'] ?? null;
        $membershipCardSaleableID = $bodyParams['membershipCardSaleableID'] ?? null;
        $invoiceID                = $bodyParams['invoiceID'] ?? null;

        $result = MembershipForm::addToInvoice(
            $memberID,
            // $ofpID,
            $years,
            $membershipSaleableID,
            $membershipCardSaleableID,
            $invoiceID
        );

        return [
            'membershipItemKey'     => $result[0],
            'membershipCardItemKey' => $result[1],
            'invoiceID'             => $result[2],
        ];
    }
}
