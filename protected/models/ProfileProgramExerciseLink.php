<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/17/14
 * Time: 2:39 PM
 */
class ProfileProgramExerciseLink extends CActiveRecord {
    public $date;
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('program_id, exercise_id, day', 'required'),
            array('day', 'numerical', 'integerOnly' => true),

            array('day', 'in', 'range' => array(
                ProfileProgram::MONDAY_ID,
                ProfileProgram::TUESDAY_ID,
                ProfileProgram::WEDNESDAY_ID,
                ProfileProgram::THURSDAY_ID,
                ProfileProgram::FRIDAY_ID,
                ProfileProgram::SATURDAY_ID,
                ProfileProgram::SUNDAY_ID
            )),
            array('program_id', 'exist', 'className' => 'ProfileProgram', 'attributeName' => 'id'),
            array('exercise_id', 'exist', 'className' => 'Exercise', 'attributeName' => 'id'),
            array('date','safe'),
        );
    }

    public function tableName() {
        return 'profile_program_exercise_link';
    }

    public function relations() {
        return array(
            'program' => array(self::HAS_ONE, 'ProfileProgram', array('id' => 'program_id')),
            'exercise' => array(self::HAS_ONE, 'Exercise', array('id' => 'exercise_id')),
        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

    public static function getExercises($program_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "program_id = :program_id";
        $criteria->params = array(':program_id' => $program_id);
        $criteria->with = array('exercise' => array('with' => array('muscle')));
        return ProfileProgramExerciseLink::model()->findAll($criteria);
    }

}