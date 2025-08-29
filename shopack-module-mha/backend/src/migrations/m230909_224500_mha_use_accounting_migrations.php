<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230909_224500_mha_use_accounting_migrations extends Migration
{
	public function safeUp()
	{
		Yii::$app->controller->addMigrationHistory("m230910_000000_accounting_create_accounting@mha");
		Yii::$app->controller->addMigrationHistory("m231113_000000_accounting_rename_coupon_to_discount@mha");
		Yii::$app->controller->addMigrationHistory("m231219_000000_accounting_convert_uasVoucherItemInfo@mha");
		Yii::$app->controller->addMigrationHistory("m231231_000000_accounting_change_slbAvailableFromDate_notnull@mha");
		Yii::$app->controller->addMigrationHistory("m240102_000000_accounting_create_discount_sn_usage_referrer@mha");
		Yii::$app->controller->addMigrationHistory("m240131_000000_accounting_set_status_to_draft_for_basket_items@mha");
		Yii::$app->controller->addMigrationHistory("m240619_000000_accounting_fix_saleable_beforeinsert_trigger@mha");
		Yii::$app->controller->addMigrationHistory("m240816_000000_accounting_convert_all_datetimes_to_timestamp@mha");

		return true;

		/*
		m230910_064534_mha_create_accounting
		m231113_162951_mha_rename_coupon_to_discount
		m231219_151044_mha_convert_uasVoucherItemInfo
		m231231_113559_mha_change_slbAvailableFromDate_notnull
		m240102_060725_mha_create_discount_sn_usage_referrer
		m240131_151321_mha_set_status_to_draft_for_basket_items
		m240619_070637_mha_fix_saleable_beforeinsert_trigger
		m240816_120128_mha_convert_all_datetimes_to_timestamp
		*/
	}

	public function safeDown()
	{
		echo "m230909_224500_mha_use_accounting_migrations cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{
	}

	public function down()
	{
		echo "m230909_224500_mha_use_accounting_migrations cannot be reverted.\n";
		return false;
	}
	*/
}
