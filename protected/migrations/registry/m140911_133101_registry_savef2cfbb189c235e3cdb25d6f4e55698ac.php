<?php

class m140911_133101_registry_savef2cfbb189c235e3cdb25d6f4e55698ac extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '16'; 
		$model->language = 'ru'; 
		$model->value = 'Новое место'; 
		$model->create_date = '2014-09-11 15:31:00'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140911_133101_registry_savef2cfbb189c235e3cdb25d6f4e55698ac does not support migration down.\n";
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
