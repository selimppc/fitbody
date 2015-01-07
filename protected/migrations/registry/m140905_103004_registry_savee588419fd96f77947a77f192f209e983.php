<?php

class m140905_103004_registry_savee588419fd96f77947a77f192f209e983 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '14'; 
		$model->language = 'ru'; 
		$model->value = 'Реклама на сайте'; 
		$model->create_date = '2014-09-05 13:30:04'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_103004_registry_savee588419fd96f77947a77f192f209e983 does not support migration down.\n";
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
