<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuReportType extends BaseEnum
{
  const Members		= 'M';
  const Fiancial	= 'F';

	public static $messageCategory = 'mha';

	public static $list = [
		self::Members		=> 'اعضا',
		self::Fiancial	=> 'مالی',
	];

};
