<?php

class m140905_102345_registry_save232122e2038feb1172ac49b90a6cfebc extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '7'; 
		$model->title = 'Реклама на сайте'; 
		$model->key = 'advertising'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-09-05 13:23:45'; 
		$model->parent_category_id = ''; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102345_registry_save232122e2038feb1172ac49b90a6cfebc does not support migration down.\n";
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
