<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuMemberMembershipStatus extends BaseEnum
{
	const WaitForPay = 'W';
	const Paid       = 'P';

	public static $messageCategory = 'mha';

	public static $list = [
		self::WaitForPay => 'Wait For Pay',
		self::Paid       => 'Paid',
	];

};
