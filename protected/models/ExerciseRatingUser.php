<?php

class ExerciseRatingUser extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('rating', 'numerical', 'integerOnly' => true, 'min' => 1, 'max' => 10),
        );
    }

    public function tableName() {
        return 'exercise_rating_user';
    }

    public function relations() {
        return array(
            'exerciseRel' => array(self::HAS_ONE, 'Exercise', array('id' => 'exercise_id')),
        );
    }

    public function attributeLabels() {
        return array();
    }


    public function afterSave() {
        $ratings = static::model()->findAll(array('condition' => 'exercise_id = :exerciseId', 'params' => array(':exerciseId' => $this->exercise_id)));
        $count = static::model()->count(array('condition' => 'exercise_id = :exerciseId', 'params' => array(':exerciseId' => $this->exercise_id)));
        $sum = 0;
        foreach ($ratings as $rating) {
            $sum += $rating->rating;
        }
        $exerciseRating = ($sum > 0) ? ($sum / $count) : 0;
        Exercise::model()->updateByPk($this->exercise_id, array('rating' => $exerciseRating, 'count_reviews' => $count));
        return parent::afterSave();
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

    public function scopeExercise($id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 'exercise_id = :exercise_id',
            'params' => array(':exercise_id' => $id)
        ));
        return $this;
    }

}