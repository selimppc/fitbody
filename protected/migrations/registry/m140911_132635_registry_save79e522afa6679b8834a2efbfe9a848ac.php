<?php

class m140911_132635_registry_save79e522afa6679b8834a2efbfe9a848ac extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '16'; 
		$model->title = 'Название баннера с клубами'; 
		$model->key = 'new_place_title'; 
		$model->type = 'text'; 
		$model->create_date = ''; 
		$model->parent_category_id = '1'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140911_132635_registry_save79e522afa6679b8834a2efbfe9a848ac does not support migration down.\n";
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
