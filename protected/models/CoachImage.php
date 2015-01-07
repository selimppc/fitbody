<?php

class CoachImage extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('image_id, coach_id', 'required'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('coach_id', 'exist', 'className' => 'Coach', 'attributeName' => 'id'),
            array('image_id, coach_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'coach_image';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function addCoachImage($imageId, $itemId = null) {
        if ($imageId && $itemId) {
            $coachImage = new CoachImage();
            $coachImage->image_id = $imageId;
            $coachImage->coach_id = $itemId;
            $coachImage->save();
        }
    }

}