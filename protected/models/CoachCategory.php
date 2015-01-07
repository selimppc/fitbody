<?php

class CoachCategory extends AbstractCategory {

//    const STATUS_ACTIVE = 1;
//    const STATUS_INACTIVE = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, dative, description', 'required'),
            array('title, dative, description', 'length', 'min'=> 1),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'coach_category';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Категория',
            'description' => 'Описание',
            'status' => 'Статус',
            'dative' => 'Дательный падеж'
        );
    }

    public function getCoachCategoryUrl() {
        return Yii::app()->createUrl('coaches/category/' . $this->slug);
    }

    public function afterSave() {
        Coach::changeCacheTag();
        return parent::afterSave();
    }

//    public function statusCondition($status = self::STATUS_ACTIVE) {
//        $this->getDbCriteria()->mergeWith(array(
//            'condition' => 't.status = :status',
//            'params' => array(':status' => $status),
//        ));
//        return $this;
//    }
//    public function slugCondition($slug) {
//        $this->getDbCriteria()->mergeWith(array(
//            'condition' => 't.slug = :slug',
//            'params' => array(':slug' => $slug),
//        ));
//        return $this;
//    }

//    public function getCategories($status = self::STATUS_ACTIVE) {
//        return static::model()->statusCondition($status)->cache(60 * 60)->findAll(array('order' => 't.category ASC'));
//    }

//    public function getActiveCategories() {
//        return $this->cache(60 * 60)->findAll(array(
//            'order' => 'parent_id ASC, category ASC',
//            'scopes' => 'statusCondition'
//        ));
//    }

//    protected function buildTreeArray(&$data, $rootId = 0) {
//        $tree = array();
//        foreach ($data as $id => $node) {
//            $node = $node->attributes;
//            if ($node['parent_id'] == $rootId) {
//                unset($data[$id]);
//                $node['children'] = self::buildTreeArray($data, $node['id']);
//                $tree[] = $node;
//            }
//        }
//        return $tree;
//    }

//
//    public function behaviors() {
//        return array(
//            'sluggable' => array(
//                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
//                'columns' => array('category'),
//                'unique' => true,
//                'update' => true,
//                'translit' => true
//            ),
//        );
//    }

}