<?php

class ProgramCategory extends AbstractCategory {


//    const STATUS_ACTIVE = 1;
//    const STATUS_INACTIVE = 0;
//    protected $_children;

    private $_url;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, description', 'required'),
            array('title', 'unique'),
            array('parent_id', 'numerical', 'integerOnly' => true),
            array('parent_id', 'exist', 'className' => 'ProgramCategory', 'attributeName' => 'id'),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'program_category';
    }

    public function relations() {
        return array(
            'parent'=>array(self::HAS_ONE, 'ProgramCategory', array('id' => 'parent_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
            'description' => 'Описание',
            'parent_id' => 'Родительская категория',
            'status' => 'Статус',
        );
    }

    public function getUrl() {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('programs/category/' . $this->slug);
        }
        return $this->_url;
    }

//    public function behaviors() {
//        return array(
//            'sluggable' => array(
//                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
//                'columns' => array('title'),
//                'unique' => true,
//                'update' => true,
//                'translit' => true
//            ),
//        );
//    }
//
//    public function statusCondition($status = self::STATUS_ACTIVE) {
//        $this->getDbCriteria()->mergeWith(array(
//            'condition'=> 'status = :status',
//            'params' => array(':status' => $status)
//        ));
//        return $this;
//    }
//
//    public function slugCondition($slug) {
//        $this->getDbCriteria()->mergeWith(array(
//            'condition' => 't.slug = :slug',
//            'params' => array(':slug' => $slug),
//        ));
//        return $this;
//    }
//
//
//    public function getActiveCategories() {
//        return $this->cache(60 * 60)->findAll(array(
//            'order' => 'parent_id ASC, title ASC',
//            'scopes' => 'statusCondition'
//        ));
//    }
//
//    public function getChildren() {
//        return $this->_children;
//    }
//
//    public function setChildren($value) {
//        $this->_children = $value;
//    }
//
//    public function getBranchTreeCategories($id = null, $statusActive = true) {
//        $criteria = new CDbCriteria;
//        $criteria->order = 'parent_id ASC, id ASC';
//        if ($statusActive) {
//            $criteria->condition = 'status = :status';
//            $criteria->params = array(':status' => self::STATUS_ACTIVE);
//        }
//        $categories = self::model()->findAll($criteria);
//        return $this->buildTree($categories, $id);
//    }
//
//    public function getTreeCategories($statusActive = false) {
//        $criteria = new CDbCriteria;
//        if ($statusActive) {
//            $criteria->condition = 'status = :status';
//            $criteria->params = array(':status' => self::STATUS_ACTIVE);
//        }
//        $criteria->order = 'parent_id ASC, id ASC';
//        $categories = self::model()->findAll($criteria);
//        return $this->buildTree($categories);
//    }
//
//    protected function buildTree(&$data, $rootId = 0) {
//        $tree = array();
//        foreach ($data as $id => $node) {
//            if ($node->parent_id == $rootId) {
//                unset($data[$id]);
//                $node->children = self::buildTree($data, $node->id);
//                $tree[] = $node;
//            }
//        }
//        return $tree;
//    }

    // nesting - 2 level hardcore
    public function categoriesListData(){
        $categoriesTree = $this->getTreeCategories();
        $categories = array();
        foreach($categoriesTree as $category) {
            if($category->parent_id == 0) {
                if ($category->children) {
                    $categories[$category->title] = CHtml::listData($category->children, 'id', 'title');
                } else {
                    $categories[$category->id] = $category->title;
                }
            }
        }
        return $categories;
    }
}