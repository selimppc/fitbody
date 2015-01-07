<?php

class m140915_141940_registry_saved404249ba21fcc45780bb5ca3673a18d extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '17'; 
		$model->title = 'Пароль'; 
		$model->key = 'password'; 
		$model->type = 'text'; 
		$model->create_date = ''; 
		$model->parent_category_id = '1'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140915_141940_registry_saved404249ba21fcc45780bb5ca3673a18d does not support migration down.\n";
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
