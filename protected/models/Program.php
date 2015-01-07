<?php

class Program extends CActiveRecord {

    const PAGINATION_COUNT_PROGRAMS = 12;
    const LAST_NEW_COUNT = 8;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public $valid = true;
    public $days = array();
    private $_url;
    private $_mainImageUrl;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }


    public function rules() {
        return array(
            array('title, short_description, description, category_id, image_id', 'required'),
            array('title, short_description', 'length', 'min'=> 2),
            array('title', 'unique'),
            array('status', 'numerical', 'integerOnly' => true),
            array('description_after', 'safe')

        );
    }

    public function tableName() {
        return 'program';
    }

    public function relations() {
        return array(
            'image' => array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
            'category' => array(self::HAS_ONE, 'ProgramCategory', array('id' => 'category_id')),
            'daysRel' => array(self::HAS_MANY, 'ProgramDay', array('program_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
            'description' => 'Описание',
            'description_after' => 'Описание после программы',
            'short_description' => 'Краткое Описание',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'image_id' => 'Изображение',
        );
    }
    public function afterValidate() {
        if (!Yii::app()->request->isAjaxRequest) {
            if (count($this->days)) {
                foreach ($this->days as $item) {
                    if (!$item->validate() || !$item->valid) {
                        $this->valid = false;
                    }
                }
            }
        }
        return parent::afterValidate();
    }
    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
        }
        $this->updated_at = new CDbExpression('NOW()');

        return parent::beforeSave();
    }
    
    public function afterSave() {
        if (!Yii::app()->request->isAjaxRequest) {

            ProgramDay::model()->deleteAll('program_id = :program_id', array('program_id' => $this->id));

            if (count($this->days)) {
                foreach ($this->days as $val) {
                    $val->program_id = $this->id;
                    $val->save();
                }
            }
        }

        return parent::afterSave();
    }

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

    public function getPrograms($categoryId = null) {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->with = array('image', 'category'  => array('together' => true, 'condition' => 'category.status = :categoryStatus', 'params' => array(':categoryStatus' => ProgramCategory::STATUS_ACTIVE)));
        if ($categoryId) {
            $activeCategories = ProgramCategory::model()->getBranchTreeCategories($categoryId);
            $ids = static::getChildrenCatId($activeCategories);
            $ids[] = $categoryId;
            $criteria->addInCondition('category.id', $ids);
        }
        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_PROGRAMS,
                'pageVar' => 'page'
            )
        ));
    }

    public function getMainImageUrl() {
        if ($this->_mainImageUrl === null) {
            if ($this->image) {
                $this->_mainImageUrl = '/pub/program/main/photo/200x150/' . $this->image->image_filename;
            } else {
               $this->_mainImageUrl = '/images/blank/200x150.gif';
            }
        }
        return $this->_mainImageUrl;
    }

    public function getUrl() {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('program/index', array('slug' => $this->slug));
        }
        return $this->_url;
    }

    public function getProgram($slug) {
        $criteria = new CDbCriteria();
        $criteria->with = array('category' => array('together' => true, 'condition' => 'category.status = :categoryStatus', 'params' => array(':categoryStatus' => ProgramCategory::STATUS_ACTIVE)), 'daysRel' => array('with' => array('exercisesRel' => array('together' => false, 'with' => array('exerciseRel' => array('with' => 'images', 'condition' => 'exerciseRel.status = :status', 'params' => array(':status' => Exercise::STATUS_ACTIVE))) ))));
        $criteria->condition = 't.status = :status AND t.slug = :slug';
        $criteria->params = array(':status' => self::STATUS_ACTIVE, ':slug' => $slug);
        return static::model()->find($criteria);
    }

    public function getChildrenCatId($cats) {
        $ids = array();
        foreach ($cats as $cat) {
            $ids[] = $cat->id;
            if ($cat->children) {
                $ids = array_merge($ids, static::getChildrenCatId($cat->children));
            }
        }
        return $ids;
    }

    public function addProgramPhoto($imageId, $itemId = null) {
        if ($itemId) {
            if ($item = self::findByPk($itemId)) {
                if($item->image_id) {
                    Yii::app()->image->delete($item->image_id);
                }
                $item->image_id = (int) $imageId;
                $item->update();
            }
        }
    }

    public function fetchPopularPrograms() {
        return $this->findAll(array(
            'with' => array(
                'category' => array(
                    'condition' => 'category.status = :categoryStatus',
                    'params' => array(':categoryStatus' => ProgramCategory::STATUS_ACTIVE)
                )
            ),
            'limit' => self::LAST_NEW_COUNT,
            'order' => 'rating DESC',
            'condition' => 't.status = :status',
            'params' => array(':status' => self::STATUS_ACTIVE)
        ));
    }

}