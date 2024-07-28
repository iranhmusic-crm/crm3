<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuBasicDefinitionStatus extends BaseEnum
{
	// -----------------------------------------------------
	// |A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|
	// |x| | |x| | | | | | | | | | | | | |x| | | | | | | | |
	// -----------------------------------------------------

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
