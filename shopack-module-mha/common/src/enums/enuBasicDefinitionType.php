<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuBasicDefinitionType extends BaseEnum
{
	// -----------------------------------------------------
	// |A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|
	// | | | |x| | | | |x| | | | | | | | |x|x| | | | | | | |
	// -----------------------------------------------------

  const Instrument	= 'I';
  const Sing				= 'S';
	const Research		= 'R';
  const MemberDocumentyRejectReason	= 'D';

	public static $messageCategory = 'mha';

	public static $list = [
		self::Instrument	=> 'ساز',
		self::Sing				=> 'آواز',
		self::Research		=> 'پژوهش',
		self::MemberDocumentyRejectReason	=> 'Member Document Reject Reason',
	];

};
