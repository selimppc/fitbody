<?php

class m140905_101853_registry_delete9f297d4ad45aee6e40b7acb147380ae1 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		Registry::model()->deleteByPk(2,"",array(),false);
	}

	public function down()
	{
		echo "m140905_101853_registry_delete9f297d4ad45aee6e40b7acb147380ae1 does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
