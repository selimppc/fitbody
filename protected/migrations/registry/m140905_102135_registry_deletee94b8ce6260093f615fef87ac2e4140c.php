<?php

class m140905_102135_registry_deletee94b8ce6260093f615fef87ac2e4140c extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		Registry::model()->deleteByPk(4,"",array(),false);
	}

	public function down()
	{
		echo "m140905_102135_registry_deletee94b8ce6260093f615fef87ac2e4140c does not support migration down.\n";
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
