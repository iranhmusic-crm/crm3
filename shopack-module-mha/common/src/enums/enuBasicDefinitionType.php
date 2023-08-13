<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuBasicDefinitionType extends BaseEnum
{
  const Instrument	= 'I';
  const Sing				= 'S';
	const Research		= 'R';

	public static $messageCategory = 'mha';

	public static $list = [
		self::Instrument	=> 'ساز',
		self::Sing				=> 'آواز',
		self::Research		=> 'پژوهش',
	];

};
