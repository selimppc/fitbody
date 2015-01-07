<?php

class CoachPropertyLink extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('property_id, description', 'required'),
            array('description', 'length', 'min'=> 1),
            array('property_id', 'exist', 'className' => 'CoachProperty', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'coach_property_link';
    }

    public function relations() {
        return array(
            'property'=>array(self::HAS_ONE, 'CoachProperty', array('id' => 'property_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'property_id' => 'Свойство',
            'description' => 'Описание',
        );
    }

}