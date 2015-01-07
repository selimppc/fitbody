<?php

class Video extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('filename, filename_real', 'required'),
            array('filename, filename_real', 'length', 'max'=> 255),
        );
    }

    public function tableName() {
        return 'video';
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