<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/18/14
 * Time: 5:31 PM
 */
class ProfileProgramNote extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('date', 'required'),
            array('program_id', 'exist', 'className' => 'ProfileProgram', 'attributeName' => 'id'),
            array('date', 'date', 'format'=>'yyyy-M-d'),
            array('meal, note, pharmacology','safe'),
        );
    }

    public function tableName() {
        return 'profile_program_note';
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

    public static function getNotes($program_id){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.program_id = :program_id';
        $criteria->params = array(':program_id' => $program_id);
        $notes = ProfileProgramNote::model()->findAll($criteria);

        $data = array();
        foreach($notes as $elem){
            $data[$elem->date] = $elem;
        }
        return $data;
    }

    public static function getEditNote($program_id, $date){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.program_id = :program_id AND t.date = :date";
        $criteria->params = array(':program_id' => $program_id, ':date' => $date);
        $note = ProfileProgramNote::model()->find($criteria);
        if($note)
            return $note;
        else
            return new ProfileProgramNote();
    }
}