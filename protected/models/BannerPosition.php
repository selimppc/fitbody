<?php

class BannerPosition extends CActiveRecord {

    const UPPER_RIGHT = 1;
    const BOTTOM_RIGHT = 2;
    const INDEX_CENTER = 3;
    const UPPER_LEFT = 4;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('width, height, position', 'required'),
            array('width, height', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'banner_position';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'width' => 'Ширина',
            'height' => 'Высота'
        );
    }

    public function afterFind() {
        return parent::afterFind();
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

}