<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use Yii;
use shopack\base\common\base\BaseEnum;

abstract class enuMemberKanoonStatus extends BaseEnum
{
	// const None             = 'N'; //0
	const WaitForSend      = 'S'; //1 منتظر ارسال به کانون جهت تایید/رد
	const WaitForSurvey    = 'W'; //2 ارسال شده به کانون. منتظر بررسی
	const WaitForResurvey  = 'E'; //3 ارسال شده به کانون. منتظر بررسی
	const Azmoon           = 'Z'; //4 آزمون
	const Accepted         = 'A'; //5 قبول
	const Rejected         = 'J'; //6 رد
	const Cancelled        = 'C'; // لغو عضویت
	const WaitForDocuments = 'D'; //منتظر ارسال مدارک

	public static $messageCategory = 'mha';

	public static $list = [
		// self::None               => 'None',
		self::WaitForSend      => 'Wait For Send',
		self::WaitForSurvey    => 'Wait For Survey',
		self::WaitForResurvey  => 'Wait For Resurvey',
		self::Azmoon           => 'Azmoon',
		self::Accepted         => 'Accepted',
		self::Rejected         => 'Rejected',
		self::Cancelled        => 'Cancelled',
		self::WaitForDocuments => 'Wait For Documents',
	];

	public static function getActionLabel($value)
	{
		if ($value === null)
			return null;

		$list = [
			// self::None               => 'None',
			self::WaitForSend      => Yii::t('mha', 'Change to: Wait For Send'),
			self::WaitForSurvey    => Yii::t('mha', 'Send For Survey'),
			self::WaitForResurvey  => Yii::t('mha', 'Send For Resurvey'),
			self::Azmoon           => Yii::t('mha', 'Send For Azmoon'),
			self::Accepted         => Yii::t('mha', 'Accept'),
			self::Rejected         => Yii::t('mha', 'Reject'),
			self::Cancelled        => Yii::t('mha', 'Cancel'),
			self::WaitForDocuments => Yii::t('mha', 'Wait For Documents'),
		];

		return $list[$value] ?? null;
	}

}
