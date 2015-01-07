<?php
Yii::import('admin.widgets.grid.column.BaseColumn');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/15/12
 * Time: 10:58 AM
 * To change this template use File | Settings | File Templates.
 */
class InputColumn extends BaseColumn {

	/**
	 * @var array
	 */
	protected static $initedJavaScriptGridList = array();

	/**
	 * @var int
	 */
	public $size = 3;

	/**
	 * @var array html tag attributes
	 */
	public $attributes = array();

	/**
	 * @var string field name
	 */
	public $fieldName = '{model}[{field}]';

	/**
	 * Opening html div element with defined $size
	 * for input width definition
	 *
	 * Can be used in blinking input with states such as
	 *      - f_success
	 *      - f_error
	 *      - f_warning
	 */
	protected function openInputContainer() {
		echo CHtml::openTag('div', array(
			'class' => 'span' . $this->size
		));
	}

	/**
	 * Close input container
	 */
	protected function closeInputContainer() {
		echo CHtml::closeTag('div');
	}

	/**
	 * @param $attr
	 * @param $value
	 * @param bool $checkEmpty
	 */
	protected function setAttribute($attr, $value, $checkEmpty = false) {
		if (!isset($this->attributes[$attr])) {
			$this->attributes[$attr] = $value;
		} else if ($checkEmpty && empty($this->attributes[$attr])) {
			$this->attributes[$attr] = $value;
		}
	}

	/**
	 * @param int $row
	 * @param mixed $data
	 */
	protected function renderDataCellContent($row, $data) {
		$this->attributes['value'] = $data[$this->name];
		$this->openInputContainer();
		echo CHtml::tag('input', $this->attributes);
		$this->closeInputContainer();
	}

	protected function setDefaultAttributes() {
		$this->setAttribute('name', $this->fieldName);
		$this->setAttribute('value', '');
		$this->setAttribute('type', $this->type);
	}

	protected function fetchJavaScriptColumnProperties() {
		$options = array(
			'name' => $this->name,
			'type' => $this->type,
			'fieldName' => $this->fieldName
		);
		return CJavaScript::encode($options);
	}

	protected function registerClientScript() {
		Yii::app()->clientScript->registerScript($this->grid->getId() . $this->name, "
			grid.get('{$this->grid->getId()}').addColumn({$this->fetchJavaScriptColumnProperties()});
		");
	}

	public function init() {
		parent::init();
		$this->fieldName = str_replace('{model}', get_class($this->grid->model), $this->fieldName);
		$this->fieldName = str_replace('{field}', $this->name, $this->fieldName);
		$this->setDefaultAttributes();
		$this->registerClientScript();
	}
}
