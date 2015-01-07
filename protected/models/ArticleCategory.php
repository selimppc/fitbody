<?php

class ArticleCategory extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    private $_url;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('category, description', 'required'),
            array('category, description','length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'article_category';
    }

    public function relations() {
        return array(
            'subcategories'  => array(self::HAS_MANY, 'ArticleSubcategory', array('category_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'category' => 'Категория',
            'description' => 'Описание',
            'status' => 'Статус',
        );
    }

    public function behaviors() {
        return array(
            'sluggable' => array(
                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('category'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
        );
    }

    public function beforeSave() {


        return parent::beforeSave();
    }

    public function fetchRootCategories() {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = "t.category ASC";
        $criteria->with = array('subcategories' => array('condition' => 'subcategories.status = :status', 'params' => array(':status' => ArticleSubcategory::STATUS_ACTIVE), 'order' => 'subcategories.title ASC'));
        return static::model()->findAll($criteria);
    }

    public function getUrl() {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('news/category/' . $this->slug);
        }
        return $this->_url;
    }

    public static function getBySlug($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.slug = :slug AND t.status = :status';
        $criteria->params = array(':slug' => $slug, ':status' => self::STATUS_ACTIVE);
        return static::model()->find($criteria);
    }

}