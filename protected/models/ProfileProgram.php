<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/17/14
 * Time: 1:04 PM
 */
class ProfileProgram extends CActiveRecord {
    const MONDAY_ID     = 1;
    const TUESDAY_ID    = 2;
    const WEDNESDAY_ID  = 3;
    const THURSDAY_ID   = 4;
    const FRIDAY_ID     = 5;
    const SATURDAY_ID   = 6;
    const SUNDAY_ID     = 7;

    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;
    public $sunday;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('profile_id, start_date, end_date', 'required'),
            array('profile_id', 'exist', 'className' => 'Profile', 'attributeName' => 'id'),
            array('start_date, end_date', 'date', 'format'=>'d.M.yyyy'),
            array('monday, tuesday, wednesday, thursday, friday, saturday, sunday', 'safe'),
        );
    }

    public function tableName() {
        return 'profile_program';
    }

    public function relations() {
        return array(
            'exercises' => array(self::HAS_MANY, 'ProfileProgramExerciseLink', 'program_id'),
            'Profile'=>array(self::BELONGS_TO, 'Profile', array('profile_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
        );
    }
    public function changeDates(){
        if($this->start_date) {
            $this->start_date = date('d.m.Y', strtotime($this->start_date));
        }
        if($this->end_date) {
            $this->end_date = date('d.m.Y', strtotime($this->end_date));
        }
    }

    public function beforeSave() {
        if($this->start_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->start_date);
            $this->start_date = $date->format('Y-m-d');
        }
        if($this->end_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->end_date);
            $this->end_date = $date->format('Y-m-d');
        }
        return parent::beforeSave();
    }

    public function afterSave(){
        if($this->scenario === 'edit'){
            $this->removeExercises();
        }
        if($this->scenario === 'create' || $this->scenario === 'edit'){
            $this->saveExercises(self::MONDAY_ID, json_decode($this->monday));
            $this->saveExercises(self::TUESDAY_ID, json_decode($this->tuesday));
            $this->saveExercises(self::WEDNESDAY_ID, json_decode($this->wednesday));
            $this->saveExercises(self::THURSDAY_ID, json_decode($this->thursday));
            $this->saveExercises(self::FRIDAY_ID, json_decode($this->friday));
            $this->saveExercises(self::SATURDAY_ID, json_decode($this->saturday));
            $this->saveExercises(self::SUNDAY_ID, json_decode($this->sunday));
        }
        if($this->hasEventHandler('onAfterAddProgram') && $this->scenario === 'create') {
            $event = new CModelEvent($this);
            $this->onAfterAddProgram($event);
        }
        return parent::afterSave();
    }

    public function onAfterAddProgram($event) {
        $this->raiseEvent('onAfterAddProgram', $event);
    }

    public function initDefault(){
        $this->monday = '{}';
        $this->tuesday  = '{}';
        $this->wednesday  = '{}';
        $this->thursday  = '{}';
        $this->friday  = '{}';
        $this->saturday  = '{}';
        $this->sunday  = '{}';
    }

    public function initExisted($days){
        $exercises = ProfileProgramExerciseLink::getExercises($this->id);
        foreach($exercises as $elem){
            if(!is_array($days[$elem->day]['data'])) $days[$elem->day]['data'] = array();
            $days[$elem->day]['data'][$elem->exercise_id] = array(
                'title' => FunctionHelper::upperFirst($elem->exercise->title),
                'muscle' => FunctionHelper::upperFirst($elem->exercise->muscle->accusative),
            );
        }
        foreach($days as $elem){
            $this->$elem['en'] = isset($elem['data']) ? json_encode($elem['data']) : '{}';
        }
    }

    public function saveExercises($day, $exercises){
        foreach($exercises as $key => $val){
            $model = new ProfileProgramExerciseLink();
            $model->day = $day;
            $model->program_id = $this->id;
            $model->exercise_id = $key;
            $model->save();
        }
    }

    public function removeExercises(){
        ProfileProgramExerciseLink::model()->deleteAllByAttributes(array('program_id' => $this->id));
    }

    public static function getCurrentProgram($profile_id, $days, $input_date = false){
        $criteria = new CDbCriteria();
        if($input_date){
            $criteria->condition = "t.profile_id = :profile_id AND t.end_date >= :date AND t.start_date <= :date";
            $criteria->params = array(':profile_id' => $profile_id, ':date' => $input_date);
        } else {
            $criteria->condition = "t.profile_id = :profile_id AND t.end_date >= NOW() AND t.start_date <= NOW()";
            $criteria->params = array(':profile_id' => $profile_id);
        }

        $criteria->order = "t.start_date ASC";
        $criteria->limit = 1;
        $criteria->with = array('exercises' => array('with' => array('exercise' => array('with' => array('muscle')))));
        $program = ProfileProgram::model()->find($criteria);

        if(!$program){
            return null;
        }

        $notes = ProfileProgramNote::getNotes($program->id);

        foreach($days as $key => $val){
            if($input_date){
                $time = strtotime($input_date);
            } else {
                $time = time();
            }

            if(date('w', $time) == $key){
                $date = date('Y-m-d', $time);
            } else {
                $new_time = $time + ($key - date('w', $time))*60*60*24;
                $date = date('Y-m-d', $new_time);
            }
            $days[$key]['data'] = array(
                'show' => false,
                'date' => $date,
                'exercises' => array(),
                'notes' => isset($notes[$date]) ? $notes[$date] : null
            );
        }

        foreach($program->exercises as $elem){
            $days[$elem->day]['data']['show'] = true;
            array_push($days[$elem->day]['data']['exercises'], $elem);
        }


        foreach($days as $key => $val){
            if(strtotime($val['data']['date']) > strtotime($program->end_date) || strtotime($val['data']['date']) < strtotime($program->start_date)){
                $days[$key]['data']['show'] = false;
            }
            $program->$val['en'] = $days[$key]['data'];
        }

        return $program;
    }

}