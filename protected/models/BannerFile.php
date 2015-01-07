<?php

class BannerFile extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('banner_id', 'required'),
            array('banner_id', 'exist', 'className' => 'Banner', 'attributeName' => 'id'),
            array('banner_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'banner_file';
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

    public function addBannerFile($bannerId, $itemId = null) {
        if ($itemId) {
            $materialImage = new BannerFile();
            $materialImage->banner_id = $bannerId;
            $materialImage->save();
        }
    }

}