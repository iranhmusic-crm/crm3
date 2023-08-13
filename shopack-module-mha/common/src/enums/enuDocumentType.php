<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuDocumentType extends BaseEnum
{
  const IDCard				= 'I'; //کارت ملی
  const BirthCert			= 'B'; //شناسنامه
  const MusicLicense	= 'X'; //مدرک موسیقی
  const Other					= 'O'; //سایر

	public static $messageCategory = 'mha';

	public static $list = [
		self::IDCard				=> 'کارت ملی',
		self::BirthCert			=> 'شناسنامه',
		self::MusicLicense	=> 'مدرک موسیقی',
		self::Other					=> 'سایر',
	];

};
