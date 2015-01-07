<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 10:48 AM
 */
class ShopCategory extends CActiveRecord {


    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    private $_url;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, description', 'required'),
            array('title', 'unique'),
            array('title, description', 'length', 'min'=> 1),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'shop_category';
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
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

    public static function getCategories(){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(":status" => 1);
        return ShopCategory::model()->findAll($criteria);
    }

    public static function getCategoryId($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.slug = :slug";
        $criteria->params = array(":status" => 1, ":slug" => $slug);
        $arr = ShopCategory::model()->find($criteria);
        return !empty($arr) ? $arr->id : false;
    }

    public function fetchRootCategories() {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = "t.title ASC";
        return static::model()->findAll($criteria);
    }

    public function getUrl() {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('shop/list/' . $this->slug);
        }
        return $this->_url;
    }



}