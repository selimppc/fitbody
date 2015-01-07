<?php

abstract class AbstractCategory extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    protected $_children;

    public function behaviors() {
        return array(
            'sluggable' => array(
                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('title'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
        );
    }

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 'status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function slugCondition($slug) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.slug = :slug',
            'params' => array(':slug' => $slug),
        ));
        return $this;
    }


    public function getActiveCategories() {
        return $this->cache(60 * 60)->findAll(array(
            'order' => 'parent_id ASC, title ASC',
            'scopes' => 'statusCondition'
        ));
    }

    public function getChildren() {
        return $this->_children;
    }

    public function setChildren($value) {
        $this->_children = $value;
    }

    public function getBranchTreeCategories($id = null, $statusActive = true) {
        $criteria = new CDbCriteria;
        $criteria->order = 'parent_id ASC, id ASC';
        if ($statusActive) {
            $criteria->condition = 'status = :status';
            $criteria->params = array(':status' => self::STATUS_ACTIVE);
        }
        $categories = $this->findAll($criteria);
        return $this->buildTree($categories, $id);
    }

    public function getTreeCategories($statusActive = false) {
        $criteria = new CDbCriteria;
        if ($statusActive) {
            $criteria->condition = 'status = :status';
            $criteria->params = array(':status' => self::STATUS_ACTIVE);
        }
        $criteria->order = 'parent_id ASC, id ASC';
        $categories = $this->findAll($criteria);
        return $this->buildTree($categories);
    }

    public function getCategoryBreadcrumbs($id) {
        $categories = $this->cache(60 * 60)->findAll();
        $data = array();

        foreach ($categories as $category) {
            $data[$category->id] = $category->attributes;
            $data[$category->id]['children'] = array();
        }

        $current = $data[$id];
        $parent_id = $current['parent_id'] === NULL ? "NULL" : $current['parent_id'];

        $parents[$current['title']] = array('/programs/category/' . strtolower($current['slug']));

        while (isset($data[$parent_id])) {
            $current = $data[$parent_id];
            $parent_id = $current['parent_id'] === NULL ? "NULL" : $current['parent_id'];
            $parents[$current['title']] = array('programs/category/' . strtolower($current['slug']));
        }

        return array_reverse($parents);
    }

    protected function buildTree(&$data, $rootId = 0) {
        $tree = array();
        foreach ($data as $id => $node) {
            if ($node->parent_id == $rootId) {
                unset($data[$id]);
                $node->children = self::buildTree($data, $node->id);
                $tree[] = $node;
            }
        }
        return $tree;
    }

    public function fetchRootCategories($status = self::STATUS_ACTIVE) {
        $criteria = new CDbCriteria();
        $criteria->condition = 'parent_id IS NULL AND status = :status';
        $criteria->params = array(':status' => $status);
        return $this->cache(60 * 60)->findAll($criteria);
    }
}