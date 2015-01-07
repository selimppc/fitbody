<?php


class ProgramComment extends Comment {

    // совпадает с префиксом подкласса комментария и именем класса сущности
    const TYPE_OF_COMMENT = 'Program';

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function __construct($scenario = 'insert') {
        $this->type_of_comment = self::TYPE_OF_COMMENT;
        parent::__construct($scenario);
    }

    public function relations() {
        return array_merge(parent::relations(), array(
            'material' => array(self::BELONGS_TO, self::TYPE_OF_COMMENT, 'material_id'),
        ));
    }


    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'material_id' => 'Программа',
            'user_id' => 'Пользователь'
        ));
    }

}