<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\enums;

use shopack\base\common\base\BaseEnum;

abstract class enuKanoonMembershipDegree extends BaseEnum
{
	const Continuous  = 'C'; //1 Then 'پیوسته'
	const Dependent1  = 'D'; //'1'; //2 Then 'وابسته 1'
	const Dependent2  = 'E'; //'2'; //3 Then 'وابسته 2'
	const Lover       = 'L'; //4 Then 'دوستدار'
	const Honorary    = 'H'; //5 Then 'افتخاری'
	const Code25      = 'O'; //'5'; //  Then 'کد 25'

	public static $messageCategory = 'mha';

	public static $list = [
		self::Continuous  => 'Continuous',
		self::Dependent1  => 'Dependent 1',
		self::Dependent2  => 'Dependent 2',
		self::Lover       => 'Lover',
		self::Honorary    => 'Honorary',
		self::Code25      => 'Code 25',
	];

};
