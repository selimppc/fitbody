<?php

class m140905_102130_registry_save96603ec95c07b479cd7c9df93cded37a extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '4'; 
		$model->title = 'Реклама на сайте'; 
		$model->key = 'advertising'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-09-05 13:21:30'; 
		$model->parent_category_id = '1'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102130_registry_save96603ec95c07b479cd7c9df93cded37a does not support migration down.\n";
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
