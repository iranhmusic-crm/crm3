<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuSponsorshipType extends BaseEnum
{
	// const Unknown  = 'U'; //1 'غیره'
	const Child    = 'C'; //2 'فرزند'
	const Father   = 'F'; //3 'پدر'
	const Mother   = 'M'; //4 'مادر'
	const Spouse   = 'S'; //5 'همسر'

	public static $messageCategory = 'mha';

	public static $list = [
		// self::Unknown  => 'Unknown',
		self::Child    => 'Child',
		self::Father   => 'Father',
		self::Mother   => 'Mother',
		self::Spouse   => 'Spouse',
	];

};
