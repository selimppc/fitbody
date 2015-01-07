<?php

class CoachPhone extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('phone', 'required'),
            array('description', 'safe'),
        );
    }

    public function tableName() {
        return 'coach_phone';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'phone' => 'Телефон',
            'description'  => 'Описание',
        );
    }

}