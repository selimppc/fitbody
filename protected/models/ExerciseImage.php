<?php

class ExerciseImage extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('image_id, exercise_id', 'required'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('exercise_id', 'exist', 'className' => 'Exercise', 'attributeName' => 'id'),
            array('image_id, exercise_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_image';
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

    public function addExerciseImage($imageId, $itemId = null) {
        if ($itemId) {
            $materialImage = new ExerciseImage();
            $materialImage->image_id = $imageId;
            $materialImage->exercise_id = $itemId;
            $materialImage->save();
        }
    }

}