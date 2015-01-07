<?php

class m140905_102441_registry_save7a22d1ad4d14bcb4eae605c17a6b8fa9 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '9'; 
		$model->title = 'Текст'; 
		$model->key = 'content'; 
		$model->type = 'redactor'; 
		$model->create_date = ''; 
		$model->parent_category_id = '3'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102441_registry_save7a22d1ad4d14bcb4eae605c17a6b8fa9 does not support migration down.\n";
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
