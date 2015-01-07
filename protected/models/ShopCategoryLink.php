<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 10:51 AM
 */
class ShopCategoryLink extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('category_id', 'required'),
            array('category_id', 'numerical', 'integerOnly' => true),
            array('category_id', 'exist', 'className' => 'ShopCategory', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'shop_category_link';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

}