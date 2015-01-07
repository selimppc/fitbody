<?php

class ClubImage extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('image_id, club_id', 'required'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('club_id', 'exist', 'className' => 'Club', 'attributeName' => 'id'),
            array('image_id, club_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'club_image';
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

    public function addClubImage($imageId, $itemId = null) {
        if ($itemId) {
            $materialImage = new ClubImage();
            $materialImage->image_id = $imageId;
            $materialImage->club_id = $itemId;
            $materialImage->save();
        }
    }

}