<?php

class m140905_103004_registry_save9c271d88fe114b9aef2c47f584979b48 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '15'; 
		$model->language = 'ru'; 
		$model->value = '<p>апр<br></p>'; 
		$model->create_date = '2014-09-05 13:30:04'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_103004_registry_save9c271d88fe114b9aef2c47f584979b48 does not support migration down.\n";
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
