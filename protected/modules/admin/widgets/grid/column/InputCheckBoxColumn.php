<?php
/**
 * Import column superclass
 */
Yii::import('admin.widgets.grid.column.InputColumn');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/15/12
 * Time: 1:21 PM
 * To change this template use File | Settings | File Templates.
 */
class InputCheckBoxColumn extends InputColumn {

	/**
	 * @var array
	 */
	public $headerHtmlOptions = array(
		'width' => 18
	);

	/**
	 * @var string
	 */
	public $type = 'checkbox';
}
