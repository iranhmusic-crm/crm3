<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuInsurerDocStatus extends BaseEnum
{
	// const None                      = 'N'; //0
	const WaitForSurvey             = 'W'; //1 تحت بررسی
	const Accepted                  = 'A'; //2 قبول
	const Rejected                  = 'J'; //3 رد
	const WaitForDocument           = 'F'; //4 منتظر صدور معرفی نامه
	const Documented                = 'D'; //5 معرفی نامه صادر شده
	const DocumentDeliveredToMember = 'L'; //6 معرفی نامه به عضو تحویل داده شده

	public static $messageCategory = 'mha';

	public static $list = [
		// self::None                      => 'None',
		self::WaitForSurvey             => 'Wait For Survey',
		self::Accepted                  => 'Accepted',
		self::Rejected                  => 'Rejected',
		self::WaitForDocument           => 'Wait For Document',
		self::Documented                => 'Documented',
		self::DocumentDeliveredToMember => 'Document Delivered To Member',
	];

};
