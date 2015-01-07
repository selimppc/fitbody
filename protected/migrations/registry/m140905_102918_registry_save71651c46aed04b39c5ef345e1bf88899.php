<?php

class m140905_102918_registry_save71651c46aed04b39c5ef345e1bf88899 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '8'; 
		$model->language = 'ru'; 
		$model->value = 'О проекте'; 
		$model->create_date = '2014-09-05 13:29:18'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102918_registry_save71651c46aed04b39c5ef345e1bf88899 does not support migration down.\n";
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
