<?php

class CoachClubLink extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('club_id', 'required'),
            array('club_id', 'exist', 'className' => 'Club', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'coach_club_link';
    }

    public function relations() {
        return array(
            'clubRel'=>array(self::HAS_ONE, 'Club', array('id' => 'club_id')),
            'coachRel'=>array(self::HAS_ONE, 'Coach', array('id' => 'coach_id')),
        );
    }

    public function attributeLabels() {
        return array();
    }

    public static function getClubCoaches($club_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "coachRel.status = :status AND t.club_id = :club_id";
        $criteria->params = array(':status' => 1, ':club_id' => $club_id);
        $criteria->with = array('coachRel');

        return CoachClubLink::model()->findAll($criteria);
    }

}