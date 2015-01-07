<?php

class Exercise extends CActiveRecord {

    const EXERCISES_PER_PAGE = 10;

    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 0;

    const TYPE_WITH_WEIGHT= 1;
    const TYPE_TRX = 2;

    public $instructionArray = array();
    public $validInstruction = true;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, short_description, description, muscle_id, type', 'required'),
            array('title, short_description, description, video_link', 'length', 'min'=> 2),
            array('status, muscle_id, type', 'numerical', 'integerOnly' => true),
            array('rating', 'numerical', 'max' => 10, 'min' => 0),
	        array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
	        array('muscles, instructions','safe'),
        );
    }

    public function tableName() {
        return 'exercise';
    }

    public function relations() {
        return array(
            'images'=>array(self::MANY_MANY, 'Image', 'exercise_image(exercise_id, image_id)'),
            'videos'=>array(self::MANY_MANY, 'Video', 'exercise_video(exercise_id, video_id)'),
	        'muscle' => array(self::HAS_ONE, 'Muscle', array('id' => 'muscle_id')),
	        'muscle_image' => array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
	        'muscles'  => array(self::MANY_MANY, 'Muscle', 'exercise_muscle_link(exercise_id, muscle_id)'),
            'instructions'  => array(self::HAS_MANY, 'Instruction', array('exercise_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
            'short_description' => 'Краткое Описание',
            'description' => 'Описание',
            'rating' => 'Рейтинг',
            'status' => 'Статус',
	        'image_id' => 'Изображение мышц',
	        'muscle_id'=> 'Основная группа мышц',
	        'muscles' => 'Участвующие группы мышц',
            'type'  => 'Тип',
            'instruction'   => 'Инструкция',
            'video_link'    => 'Ссылка на видео'
        );
    }

    public function afterSave() {
	    if(!Yii::app()->request->isAjaxRequest){
		    ExerciseMuscleLink::model()->deleteAll('exercise_id = :exercise_id', array(':exercise_id' => $this->id));
            if(is_array($this->muscles)){
                foreach($this->muscles as $val){
                    $muscle = new ExerciseMuscleLink();
                    $muscle->muscle_id = $val;
                    $muscle->exercise_id = $this->id;
                    $muscle->save();
                }
            }
            Instruction::model()->deleteAll('exercise_id = :exercise_id', array(':exercise_id' => $this->id));
            foreach($this->instructionArray as $elem){
                $elem->exercise_id = $this->id;
                $elem->save();
            }
	    }
        parent::afterSave();
    }

	public function afterValidate () {
		if(!Yii::app()->request->isAjaxRequest){
            if(is_array($this->muscles)){
                foreach($this->muscles as $val){
                    $muscle = new ExerciseMuscleLink();
                    $muscle->muscle_id = $val;
                    if(!$muscle->validate(array('muscle_id'))){
                        $this->addError('muscles', implode('. ',$muscle->errors['muscle_id']));
                        return false;
                    }
                }
            }

            if($this->instructionArray){
                foreach ($this->instructionArray as $key => $elem) {
                    if (!$elem->validate(array('title'))) {
                        $this->validInstruction = false;
                    }
                }
            }
		}
		return parent::afterValidate();
	}

    public static function getAll(){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        return static::model()->with('images','muscle')->findAll($criteria);
    }

    public static function getData($type_id, $muscle_id = null){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status AND t.type = :type';
        $criteria->params = array(':status' => self::STATUS_ACTIVE, ':type' => $type_id);
        if($muscle_id){
            $criteria->addCondition('t.muscle_id = :muscle_id');
            $criteria->params[':muscle_id'] = $muscle_id;
        }
        $criteria->order = 't.rating DESC';
        $criteria->with = array('images','muscle','muscles');

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::EXERCISES_PER_PAGE,
                'pageVar' => 'page'
            )
        ));
    }

    public function getImageUrlListExercise($filename) {
        return '/pub/exercise/photo/131x131/' . $filename;
    }

    public function getImagesExercise($filename) {
        return '/pub/exercise/photo/414x280/'  . $filename;
    }

    public function getImageOtherExercise($filename) {
        return '/pub/exercise/photo/190x140/' . $filename;
    }



}