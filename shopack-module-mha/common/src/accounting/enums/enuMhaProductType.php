<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\accounting\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuMhaProductType extends BaseEnum
{
	const Membership			= 'M';
	const MembershipCard  = 'C';
	const PostPacket 			= 'P';

	public static $messageCategory = 'mha';

	public static $list = [
		self::Membership 			=> 'Membership',
		self::MembershipCard  => 'Membership Card',
		self::PostPacket 			=> 'Post Packet',
	];

};
