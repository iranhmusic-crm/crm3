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
		self::WaitForApprove => 'Wait For Approve',
		self::Approved       => 'Approved',
		self::Rejected       => 'Rejected',
	];

};
