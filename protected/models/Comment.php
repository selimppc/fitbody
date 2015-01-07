<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/2/14
 * Time: 3:23 PM
 * To change this template use File | Settings | File Templates.
 */

/**
 * This is the model class for table "comment".
 *
 * @property string $id
 * @property string $type
 * @property integer $material_id
 * @property string $text
 * @property string $date
 * @property integer $user_id
 * @property integer $parent_id
 * @property integer $status
 */
class Comment extends CActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $type_of_comment = '';
    protected $_children;

    public function getChildren() {
        return $this->_children;
    }

    public function setChildren($value) {
        $this->_children = $value;
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


	public function tableName() {
		return 'comment';
	}

	protected function instantiate($attributes) {
		$class = $attributes['type'] . 'Comment';
		$model = new $class(null);
		return $model;
	}

	public function rules() {
		return array(
			array('text', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
			array('material_id, user_id, type', 'safe', 'on'=>'search'),
            array('parent_id', 'exist', 'className' => 'Comment', 'attributeName' => 'id', 'criteria' => array('condition' => 'type = :type', 'params' => array(':type' => $this->type_of_comment))),
		);
	}

	public function relations() {
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'parent' => array(self::BELONGS_TO, 'Comment', 'parent_id'),
		);
	}

	public function type($type) {
		if ($type){
			$this->getDbCriteria()->mergeWith(array(
				'condition' => 'type = :type',
				'params' => array(':type' => $type),
			));
		}
		return $this;
	}

    protected function initType() {
        if (!$this->type)
            $this->type = $this->type_of_comment;
    }

    public function findAllByAttributes($attributes, $condition = '', $params = array()) {
        $this->type($this->type_of_comment);
        return parent::findAllByAttributes($attributes, $condition, $params);
    }

    public function findAll($condition = '', $params=array()) {
        $this->type($this->type_of_comment);
        return parent::findAll($condition, $params);
    }

    public function find($condition = '', $params=array()) {
        $this->type($this->type_of_comment);
        return parent::find($condition, $params);
    }

    public function count($condition = '', $params=array()) {
        $this->type($this->type_of_comment);
        return parent::count($condition, $params);
    }

    public function beforeSave() {
        $this->initType();
        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

    public function getTreeComments($id) {
        $criteria = new CDbCriteria;
        $criteria->condition = 't.status = :status AND t.material_id = :id';
        $criteria->with = array('user' => array('with' => array('image')));
        $criteria->params = array(':status' => self::STATUS_ACTIVE, ':id' => $id);
        $criteria->order = 't.parent_id ASC, t.id ASC';
        $categories = static::model()->findAll($criteria);
        return $this->buildTree($categories);
    }


}