<?php

class ProgramDay extends CActiveRecord {

    public $exercises;
    public $valid = true;

    public $daysOfWeek = array(1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница', 6 => 'Суббота', 7 => 'Воскресенье');
    public $daysOfWeekOrdinal = array(1 => 'Первый день', 2 => 'Второй день', 3 => 'Третий день', 4 => 'Четвертый день', 5 => 'Пятый день', 6 => 'Шестой день', 7 => 'Седьмой день');

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getDaysOfWeek() {
        return $this->daysOfWeek;
    }

    public function getDaysOfWeekOrdinal() {
        return $this->daysOfWeekOrdinal;
    }

    public function rules() {
        return array(
            array('day_of_week', 'numerical', 'integerOnly' => true, 'min' => 1, 'max' => 7),
            array('description', 'length', 'min' => 3),
        );
    }

    public function tableName() {
        return 'program_day';
    }

    public function relations() {
        return array(
            'programRel' => array(self::HAS_ONE, 'Program', array('id' => 'program_id')),
            'exercisesRel' => array(self::HAS_MANY, 'ProgramDayExercise', array('program_day_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'description' => 'Текст описания'
        );
    }


    public function afterValidate() {
        if (!Yii::app()->request->isAjaxRequest) {
            if (count($this->exercises) > 0) {
                foreach ($this->exercises as $item) {
                    if (!$item->validate()) {
                        $this->valid = false;
                    }
                }
            }
        }
        return parent::afterValidate();
    }

    public function afterSave() {
        if (!Yii::app()->request->isAjaxRequest) {
            if (count($this->exercises)) {
                foreach ($this->exercises as $val) {
                    $val->program_day_id = $this->id;
                    $val->save();
                }
            }
        }

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