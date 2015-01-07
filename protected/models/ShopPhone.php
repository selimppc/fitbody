<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 11:08 AM
 */
class ShopPhone extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('phone', 'required'),
        );
    }

    public function tableName() {
        return 'shop_phone';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'phone' => 'Телефон',
            'description'  => 'Контактное лицо',
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

}