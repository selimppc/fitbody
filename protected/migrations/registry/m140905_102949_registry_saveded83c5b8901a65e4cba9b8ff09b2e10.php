<?php

class m140905_102949_registry_saveded83c5b8901a65e4cba9b8ff09b2e10 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '12'; 
		$model->language = 'ru'; 
		$model->value = 'Контакты'; 
		$model->create_date = '2014-09-05 13:29:49'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102949_registry_saveded83c5b8901a65e4cba9b8ff09b2e10 does not support migration down.\n";
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
