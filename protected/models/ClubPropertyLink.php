<?php

class ClubPropertyLink extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('club_property_id, description', 'required'),
            array('description', 'length', 'min'=> 1),
            array('club_property_id', 'exist', 'className' => 'ClubProperty', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'club_property_link';
    }

    public function relations() {
        return array(
            'property'=>array(self::HAS_ONE, 'ClubProperty', array('id' => 'club_property_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'club_property_id' => 'Параметр',
            'description' => 'Описание',
        );
    }



    public function beforeSave() {

        return parent::beforeSave();
    }



}