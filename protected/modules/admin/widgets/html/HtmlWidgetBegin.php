<?php
//  include superclass
Yii::import('admin.widgets.html.HtmlBase');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 12:55 PM
 * To change this template use File | Settings | File Templates.
 */
class HtmlWidgetBegin extends HtmlBase {

	public static $_boxes = 1;

	public $class = 'w-box';

	/**
	 * Must return tag name
	 *
	 * @example div, li, ul
	 *
	 * @return string
	 */
	protected function tag() {
		return 'div';
	}

	/**
	 * Must return html options
	 *
	 * @see CHtml::openTag
	 *
	 * @return array
	 */
	protected function tagOptions() {
		parent::addClass($this->class);
		$this->htmlOptions['id'] = 'w_sort' . self::$_boxes;
		self::$_boxes++;
		return $this->htmlOptions;
	}

	protected function openTag() {
		parent::openTag();
	}

	protected function closeTag() {}
}
