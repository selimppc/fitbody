<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/28/13
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */
class FolderForm extends CFormModel {

	public $id;

	public $title;
	public $key;
	public $parent_category_id;

	public function rules() {
		return array(
			array('title, key', 'required'),
			array('title, key', 'length', 'min'=>2, 'max' => 255),
			array('key','match','allowEmpty'=>false,'pattern'=>'/^[A-Za-z0-9_]+$/'),
			array('parent_category_id','exist','className'=>'Registry','allowEmpty'=>true,'attributeName'=>'id'),
			array('key','uniqueKey')
		);
	}

	public function uniqueKey($attribute, $params) {
		if (!$this->hasErrors()) {
			$criteria = new CDbCriteria();
			$criteria->condition = '`key`=:key';
			$criteria->condition .= ($this->parent_category_id) ? ' AND parent_category_id = '.$this->parent_category_id : ' AND parent_category_id IS NULL';
			$criteria->params = array(':key'=>$this->key);
			$data = Registry::model()->findAll($criteria);
			if(count($data))
				$this->addError('key', Yii::t('admin', 'Key must be unique.'));
		}
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
			'title' => Yii::t('login', 'Folder title'),
			'key' => Yii::t('admin', 'Folder key'),
		);
	}

	public function save()
	{
		if($this->validate()) {
			$model = new Registry();
			$model->title = $this->title;
			$model->key = $this->key;
			$model->type = 'folder';
			$model->create_date = date('Y-m-d H:i:s');
			$model->parent_category_id = $this->parent_category_id;
			if(!$model->save())
			{
				$this->addErrors($model->errors);
				return false;
			}

			$this->id = $model->id;
			return true;
		}
		return false;
	}
}