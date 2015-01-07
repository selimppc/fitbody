<?php

class ExerciseVideo extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('video_id, exercise_id', 'required'),
            array('video_id', 'exist', 'className' => 'Video', 'attributeName' => 'id'),
            array('exercise_id', 'exist', 'className' => 'Exercise', 'attributeName' => 'id'),
            array('video_id, exercise_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_video';
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

    public function addExerciseVideo($videoId, $itemId = null) {
        if ($itemId) {
            $materialImage = new ExerciseVideo();
            $materialImage->video_id = $videoId;
            $materialImage->exercise_id = $itemId;
            $materialImage->save();
        }
    }

}