<?php

class m140905_102918_registry_save0f1a3857ce259f8b08cc996e41493411 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '9'; 
		$model->language = 'ru'; 
		$model->value = '<p>фыв<br></p>'; 
		$model->create_date = '2014-09-05 13:29:18'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102918_registry_save0f1a3857ce259f8b08cc996e41493411 does not support migration down.\n";
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
