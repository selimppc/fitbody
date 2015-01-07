<?php

class ClubPhone extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('phone', 'required'),
        );
    }

    public function tableName() {
        return 'club_phone';
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