<?php

class m140905_102650_registry_savef20bbe3df4a7d5598b0f573bc43b3e26 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '11'; 
		$model->title = 'Текст'; 
		$model->key = 'content'; 
		$model->type = 'redactor'; 
		$model->create_date = ''; 
		$model->parent_category_id = '5'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102650_registry_savef20bbe3df4a7d5598b0f573bc43b3e26 does not support migration down.\n";
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
