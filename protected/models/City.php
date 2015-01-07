<?php

class City extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('city', 'required'),
            array('city', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'city';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'city' => 'Город',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}