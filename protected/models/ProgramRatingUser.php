<?php

class ProgramRatingUser extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('rating', 'numerical', 'integerOnly' => true, 'min' => 1, 'max' => 10),
        );
    }

    public function tableName() {
        return 'program_rating_user';
    }

    public function relations() {
        return array(
            'programRel' => array(self::HAS_ONE, 'Program', array('id' => 'program_id')),
        );
    }

    public function attributeLabels() {
        return array();
    }


    public function afterSave() {
        $ratings = static::model()->findAll(array('condition' => 'program_id = :programId', 'params' => array(':programId' => $this->program_id)));
        $count = static::model()->count(array('condition' => 'program_id = :programId', 'params' => array(':programId' => $this->program_id)));
        $sum = 0;
        foreach ($ratings as $rating) {
            $sum += $rating->rating;
        }
        $programRating = ($sum > 0) ? ($sum / $count) : 0;
        Program::model()->updateByPk($this->program_id, array('rating' => $programRating, 'count_reviews' => $count));
        return parent::afterSave();
    }
    public function beforeSave() {

        return parent::beforeSave();
    }

    public function scopeProgram($id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 'program_id = :program_id',
            'params' => array(':program_id' => $id)
        ));
        return $this;
    }

}