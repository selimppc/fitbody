<?php

class CoachCategoryLink extends CActiveRecord {


    const MAIN_CATEGORY = 1;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('category_id', 'required'),
            array('category_id', 'numerical', 'integerOnly' => true),
            array('category_id', 'exist', 'className' => 'CoachCategory', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'coach_category_link';
    }

    public function relations() {
        return array(
            'category'=>array(self::HAS_ONE, 'CoachCategory', array('id' => 'category_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'category_id' => 'Категория'
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}