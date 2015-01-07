<?php

class ClubDestinationLink extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('destination_id', 'required'),
            array('destination_id', 'numerical', 'integerOnly' => true),
            array('destination_id', 'exist', 'className' => 'ClubDestination', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'club_destination_link';
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