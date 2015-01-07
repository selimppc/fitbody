<?php

class m140905_102626_registry_save46216b8135b2782b031ea536d4921ee1 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '10'; 
		$model->language = 'ru'; 
		$model->value = ''; 
		$model->create_date = '2014-09-05 13:26:26'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102626_registry_save46216b8135b2782b031ea536d4921ee1 does not support migration down.\n";
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
