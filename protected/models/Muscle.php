<?php

class Muscle extends CActiveRecord {

    private $_url;
    private $_mainImageMusclePath;
    private $_mainImageCategoryMusclePath;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('muscle, accusative, slug, description', 'required'),
            array('muscle, accusative, slug, description', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
            array('slug','unique'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'muscle';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'name' => 'Название',
            'status' => 'Статус',
            'accusative' => 'Винительный падеж',
            'slug' => 'uri',
            'image_id' => 'Изображение',
            'description' => 'Описание',
        );
    }

    public function addMusclePhoto($imageId, $itemId = null) {
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

    public static function getAll() {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = 't.muscle ASC';
        return Muscle::model()->with('image')->findAll($criteria);
    }

    public static function getMuscle($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.slug = :slug';
        $criteria->params = array(':slug' => $slug);
        return static::model()->find($criteria);
    }

    public function fetchMuscles($limit = 15) {
        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = 't.muscle';
        return Muscle::model()->findAll($criteria);
    }

    public function getUrl() {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('exercises/with-weights/' . $this->slug);
        }
        return $this->_url;
    }

    public function getMainImageMusclePath() {
        if ($this->_mainImageMusclePath === null) {
            if ($this->image_id) {
                $this->_mainImageMusclePath = '/pub/muscle/image/213x400/' . $this->image->image_filename;
            } else {
                $this->_mainImageMusclePath = '/images/blank/213x400.gif';
            }
        }
        return $this->_mainImageMusclePath;
    }

    public function getMainImageCategoryMusclePath() {
        if ($this->_mainImageCategoryMusclePath === null) {
            if ($this->image_id) {
                $this->_mainImageCategoryMusclePath = '/pub/muscle/image/150x214/' . $this->image->image_filename;
            } else {
                $this->_mainImageCategoryMusclePath = '/images/blank/150x214.gif';
            }
        }
        return $this->_mainImageCategoryMusclePath;
    }

}