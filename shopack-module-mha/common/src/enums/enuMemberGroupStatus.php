<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuMemberGroupStatus extends BaseEnum
{
  const Active 		= 'A';
  const Inactive 	= 'D';
  const Removed 	= 'R';

	public static $messageCategory = 'aaa';

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
