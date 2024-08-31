<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnumWithADR;

abstract class enuSpecialtyStatus extends BaseEnumWithADR
{
	// B: used in base class
	// -----------------------------------------------------
	// |A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|
	// |B| | |B| | | | | | | | | | | | | |B| | | | | | | | |
	// -----------------------------------------------------

	public static $messageCategory = 'mha';

	public static $list = [
		// [
			self::Active            => 'Active',
			self::Inactive          => 'Inactive',
			self::Removed           => 'Removed',
		// ],
		// 'create-form' => [
		// 	self::Active,
		// 	self::Inactive,
		// ],
		// 'update-form' => [
		// 	self::Active,
		// 	self::Inactive,
		// ],
	];

};
