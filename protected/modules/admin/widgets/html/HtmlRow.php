<?php
//  include superclass
Yii::import('admin.widgets.html.HtmlBase');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 12:08 PM
 * To change this template use File | Settings | File Templates.
 */
class HtmlRow extends HtmlBase {

	/**
	 * @var string
	 */
	public $class = 'row-fluid';

	/**
	 * @return string
	 */
	protected function tag() {
		return 'div';
	}

	/**
	 * @return array
	 */
	protected function tagOptions() {
		parent::addClass($this->class);
		return $this->htmlOptions;
	}
}
