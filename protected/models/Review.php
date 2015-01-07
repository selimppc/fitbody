<?php

/**
 * This is the model class for table "review".
 *
 * @property string $id
 * @property string $type
 * @property integer $material_id
 * @property string $review
 * @property integer $user_id
 * @property integer $status
 * @property string $created_at
 */
class Review extends CActiveRecord
{
    protected $type_of_review = '';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public function tableName()
    {
        return 'review';
    }
    protected function instantiate($attributes)
    {
        $class = $attributes['type'] . 'Review';
        $model = new $class(null);
        return $model;
    }

    public function rules()
    {
        return array(
            array('review, rating, material_id', 'required'),
            array('rating', 'numerical', 'min' => 0, 'max' => 5),
            array('user_id', 'required', 'on' => 'adminScenario'),
            array('status', 'in', 'range' => array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function relations()
    {
        return array(
            'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'review' => 'Отзыв',
            'status' => 'Статус',
            'rating' => 'Рейтинг'
        );
    }

    public function type($type)
    {
        if ($type) {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => 'type = :type',
                'params' => array(':type' => $type),
            ));
        }
        return $this;
    }

    public function findAllByAttributes($attributes, $condition='', $params=array())
    {
        $this->type($this->type_of_review);
        return parent::findAllByAttributes($attributes, $condition, $params);
    }

    public function findAll($condition='', $params=array())
    {
        $this->type($this->type_of_review);
        return parent::findAll($condition, $params);
    }

    public function find($condition='', $params=array())
    {
        $this->type($this->type_of_review);
        return parent::find($condition, $params);
    }

    public function count($condition='', $params=array())
    {
        $this->type($this->type_of_review);
        return parent::count($condition, $params);
    }


    protected function initType() {
        if (!$this->type)
            $this->type = $this->type_of_review;
    }

    public function beforeSave() {
        $this->initType();
        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
        }
        $this->updated_at = new CDbExpression('NOW()');
        return parent::beforeSave();
    }


}