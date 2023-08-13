<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuInsurerStatus extends BaseEnum
{
  const Active 		= 'A';
  const Inactive 	= 'D';
  const Removed 	= 'R';

	public static $messageCategory = 'mha';

	public static $list = [
		[
			self::Active		=> 'Active',
			self::Inactive	=> 'Inactive',
			self::Removed 	=> 'Removed',
		],
		'form' => [
			self::Active,
			self::Inactive,
		],
	];

};
