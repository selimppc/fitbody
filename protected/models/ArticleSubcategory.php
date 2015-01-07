<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 5/29/14
 * Time: 1:55 PM
 * To change this template use File | Settings | File Templates.
 */
class ArticleSubcategory extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function rules() {
		return array(
			array('title, description, category_id', 'required'),
			array('title, description','length', 'min'=> 2),
			array('status, category_id', 'numerical', 'integerOnly' => true),
			array('title','unique'),

		);
	}

	public function tableName() {
		return 'article_subcategory';
	}

	public function relations() {
		return array(
			'category' => array(self::BELONGS_TO, 'ArticleCategory', 'category_id'),
		);
	}

	public function attributeLabels() {
		return array(
			'title' => 'Название',
			'category_id' => 'Категория',
			'description' => 'Описание',
			'status' => 'Статус',
		);
	}

    public function behaviors() {
        return array(
            'sluggable' => array(
                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('title'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
        );
    }

	public function beforeSave() {
		return parent::beforeSave();
	}

    public static function getBySlug($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.slug = :slug AND t.status = :status';
        $criteria->params = array(':slug' => $slug, ':status' => self::STATUS_ACTIVE);
        return static::model()->find($criteria);
    }


}