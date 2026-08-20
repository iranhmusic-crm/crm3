<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\accounting\models;

use Yii;
use yii\base\Model;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\base\common\helpers\HttpHelper;
// use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
// use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
// use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;

class MembershipForm extends Model
{
    public $startDate;
    public $endDate;
    public $years;
    public $unitPrice;
    public $totalPrice;
    public $saleableID;
    public $discountCode;
    public $printCard = true;
    public $printCardAmount;

    public function rules()
    {
        return [
            ['discountCode', 'string'],
            ['printCard', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'startDate'                => Yii::t('app', 'Start Date'),
            'endDate'                    => Yii::t('app', 'End Date'),
            'years'                        => Yii::t('app', 'Year'),
            'unitPrice'                => Yii::t('aaa', 'Unit Price'),
            'totalPrice'            => Yii::t('aaa', 'Total Price'),
            'saleableID'            => Yii::t('aaa', 'Saleable'),
            'discountCode'        => Yii::t('aaa', 'Discount Code'),
            'printCard'                => Yii::t('mha', 'Print Card'),
            'printCardAmount'    => Yii::t('mha', 'Card Print Price'),
        ];
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName))
            return true;

        $info = self::getRenewalInfo();

        $startDate       = $info['startDate'];
        $endDate         = $info['endDate'];
        $years           = $info['years'];
        $unitPrice       = $info['unitPrice'];
        $totalPrice      = $info['totalPrice'];
        $saleableID      = $info['saleableID'];
        $printCardAmount = $info['printCardAmount'];

        $this->startDate       = $startDate;
        $this->endDate         = $endDate;
        $this->years           = $years;
        $this->unitPrice       = $unitPrice;
        $this->totalPrice      = $totalPrice;
        $this->saleableID      = $saleableID;
        $this->printCardAmount = $printCardAmount;

        return false;
    }

    public static function getRenewalInfo()
    {
        $apiResponse = HttpHelper::callApi(
            'mha/accounting/membership/renewal-info',
            HttpHelper::METHOD_GET,
            // [
            // 	'memberID' => Yii::$app->user->id,
            // ]
        );

        HttpHelper::throwApiResponseIfFailed($apiResponse, 'mha');

		return [
			'startDate'       => $apiResponse['body']['startDate'],
			'endDate'         => $apiResponse['body']['endDate'],
			'years'           => $apiResponse['body']['years'],
			'unitPrice'       => $apiResponse['body']['unitPrice'],
			'totalPrice'      => $apiResponse['body']['totalPrice'],
			'saleableID'      => $apiResponse['body']['saleableID'],
			'printCardAmount' => $apiResponse['body']['printCardAmount'],
		];
	}

    public function addToBasket($basketdata, $saleableID = null)
    {
        try {
            $apiResponse = HttpHelper::callApi(
                'mha/accounting/membership/add-to-basket',
                HttpHelper::METHOD_POST,
                [],
                [
                    'basketdata' => $basketdata,
                    'printCard' => $this->printCard,
                    'discountCode' => $this->discountCode,
                ]
            );

            HttpHelper::throwApiResponseIfFailed($apiResponse, 'mha');

            return $apiResponse['body'];

            // $newBase64Basketdata = $apiResponse['body']['basketdata'];
            // return $newBase64Basketdata;

        } catch (\Throwable $th) {
            if (YII_ENV_DEV)
                throw $th;

            $this->addError('', Yii::t('mha', $th->getMessage()));
            return false;
        }
    }
}
