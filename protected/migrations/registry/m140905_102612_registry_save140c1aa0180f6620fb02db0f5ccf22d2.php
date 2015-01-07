<?php

class m140905_102612_registry_save140c1aa0180f6620fb02db0f5ccf22d2 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '10'; 
		$model->title = 'Название'; 
		$model->key = 'title'; 
		$model->type = 'text'; 
		$model->create_date = ''; 
		$model->parent_category_id = '5'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102612_registry_save140c1aa0180f6620fb02db0f5ccf22d2 does not support migration down.\n";
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
