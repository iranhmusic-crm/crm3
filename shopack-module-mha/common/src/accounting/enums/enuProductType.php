<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\accounting\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuProductType extends BaseEnum
{
	const Membership = 'M';
	const CardPrint  = 'C';
	const PostPacket = 'P';

	public static $messageCategory = 'mha';

	public static $list = [
		self::Membership => 'Membership',
		self::CardPrint  => 'Card Print',
		self::PostPacket => 'Post Packet',
	];

};
