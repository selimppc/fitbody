<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 12.07.14
 * Time: 12:12
 * Comment: Yep, it's magic
 */


class Calculator extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    private $_calculatorUrl;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, description, short_description, image_id', 'required'),
            array('title, description, short_description','length', 'min'=> 3),
            array('title','length', 'max'=> 255),
            array('image_id', 'numerical', 'integerOnly' => true),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('calculator_code', 'safe'),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'calculator';
    }

    public function relations() {
        return array(
            'image' => array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Заголовок',
            'image_id' => 'Изображение',
            'short_description' => 'Краткое Описание',
            'description' => 'Описание',
            'calculator' => 'Код калькулятора',
            'status' => 'Статус',
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
            'condition'=> 'slug = :slug',
            'params' => array(':slug' => $slug)
        ));
        return $this;
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
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            )
        );
    }

    public function addCalculatorPhoto($imageId, $itemId = null) {
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

    public function deleteByPk($pk,$condition = '',$params = array()){
        $items = self::findAllByPk($pk);
        foreach($items as $key => $oneItem){
            if($oneItem->image_id){
                Yii::app()->image->delete($oneItem->image_id);
            }
        }
        return parent::deleteByPk($pk,$condition,$params);
    }

    public function getCalculators() {
        return $this->statusCondition()->findAll(array('with' => 'image'));
    }

    public function getCalculator($slug) {
        return $this->statusCondition()->slugCondition($slug)->find(array('with' => 'image'));
    }


    public function getCalculatorUrl() {
        if ($this->_calculatorUrl === null) {
            $this->_calculatorUrl = Yii::app()->createUrl('calculator/index', array('slug' => $this->slug));
        }
        return $this->_calculatorUrl;
    }

}