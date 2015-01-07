<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 12.07.14
 * Time: 14:19
 * Comment: Yep, it's magic
 */

class BookCategory extends AbstractCategory {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, title_dative, description', 'required'),
            array('title, title_dative, description', 'length', 'min'=> 1),
            array('title', 'length', 'max'=> 255),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'book_category';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Категория',
            'title_dative' => 'Категория в Дательном падеже',
            'description' => 'Описание',
            'status' => 'Статус',
        );
    }

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 't.status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function slugCondition($slug) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 'slug = :slug',
            'params' => array(':slug' => $slug)
        ));
        return $this;
    }

//    public function fetchRootCategories() {
//        return $this->statusCondition()->cache(60 * 60)->findAll(array('condition' => 'parent_id IS NULL'));
//    }

    public function fetchCategories() {
        return $this->statusCondition()->findAll();
    }

}