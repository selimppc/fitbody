<?php
Yii::import('admin.widgets.grid.column.InputColumn');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/15/12
 * Time: 1:23 PM
 * To change this template use File | Settings | File Templates.
 */

/**
 *
 */
class SelectColumn extends InputColumn {

	/**
	 * Select options container for configuring select
	 *
	 * @see CHtml::dropDownList
	 * @var array
	 */
	public $options = array();

	/**
	 * @param int $row
	 * @param mixed $data
	 */
	protected function renderDataCellContent($row, $data) {
		echo CHtml::dropDownList($this->grid->model->tableName() . '[' . $this->name . ']', $data[$this->name], $this->options, $this->attributes);
	}

	protected function setDefaultAttributes() {}
}
