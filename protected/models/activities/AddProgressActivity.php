<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 28.07.14
 * Time: 11:00
 * Comment: Yep, it's magic
 */

class AddProgressActivity extends AbstractUserActivity {

    const TYPE_OF_ACTIVITY = 'AddProgress';
    const TABLE_OF_RELATION = 'ProfileProgress';

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function __construct($scenario = 'insert') {
        $this->type_of_activity = self::TYPE_OF_ACTIVITY;
        parent::__construct($scenario);
    }

    public function rules () {
        return array_merge(parent::rules(), array(
            array('source_id', 'exist', 'className' => self::TABLE_OF_RELATION, 'attributeName' => 'id'),
        ));
    }

    public function relations() {
        return array_merge(parent::relations(), array(
            'material' => array(self::BELONGS_TO, self::TABLE_OF_RELATION, 'source_id'),
        ));
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'source_id' => 'Прогресс',
            'user_id' => 'Пользователь'
        ));
    }

    public function addActivity($event) {
        $progress = $event->sender;
        $this->user_id = $progress->profile->user_id;
        $this->source_id = $progress->id;
        $this->save();
    }

}