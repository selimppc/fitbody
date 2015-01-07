<?php

class m140905_102935_registry_save63e4957e657e3ded0798b0243ffc4b2d extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '10'; 
		$model->language = 'ru'; 
		$model->value = 'Партнерам'; 
		$model->create_date = '2014-09-05 13:29:35'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102935_registry_save63e4957e657e3ded0798b0243ffc4b2d does not support migration down.\n";
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
