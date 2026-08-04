<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuMemberDocumentStatus extends BaseEnum
{
	const WaitForApprove = 'W';
	const Approved       = 'A';
	const Rejected       = 'J';

	public static $messageCategory = 'mha';

	public static $list = [
		self::WaitForApprove => 'منتظر تایید', //'Wait For Approve',
		self::Approved       => 'تایید شده', //'Approved',
		self::Rejected       => 'رد شده', //'Rejected',
	];

};
