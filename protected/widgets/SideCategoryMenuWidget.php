<?php

class SideCategoryMenuWidget extends CWidget {

    public $categoryModel;
    public $slug;
    public $url;
    public $parentField = 'parent_id';
    public $titleField = 'title';
    public function init() {}

    public function run() {
        $categories = CActiveRecord::model($this->categoryModel)->getActiveCategories();
        $categories = FunctionHelper::buildTreeArray($categories, 0, $this->parentField);
        echo FunctionHelper::getTreeMenuCategory($categories, $this->slug, false, $this->titleField, $this->url);
    }
}