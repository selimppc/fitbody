<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/28/13
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */
class ElementForm extends CFormModel {

	public $id;

	public $title;
	public $key;
	public $type;
	public $parent_category_id;

	public function rules() {
		return array(
			array('title, key, type,parent_category_id', 'required'),
			array('title, key', 'length', 'min'=>2, 'max' => 255),
			array('type','in','range'=>Registry::model()->typeListKeys),
			array('parent_category_id','exist','className'=>'Registry','allowEmpty'=>false,'attributeName'=>'id'),
			array('key','match','allowEmpty'=>false,'pattern'=>'/^[A-Za-z0-9_]+$/'),
			array('key','uniqueKey')
		);
	}

	public function uniqueKey($attribute, $params) {
		if (!$this->hasErrors()) {
			$criteria = new CDbCriteria();
			$criteria->condition = '`key`=:key  AND parent_category_id = :parentId';
			$criteria->params = array(':key'=>$this->key,':parentId'=>$this->parent_category_id);
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
			'title' => Yii::t('login', 'Element title'),
			'key' => Yii::t('admin', 'Element key'),
			'type' => Yii::t('admin', 'Element type'),
		);
	}

	public function save()
	{
		if($this->validate()) {
			$model = new Registry();
			$model->title = $this->title;
			$model->key = $this->key;
			$model->type = $this->type;
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