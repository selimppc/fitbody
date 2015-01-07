<?php
//  include superclass
Yii::import('admin.widgets.html.HtmlBase');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 1:57 PM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Only close
 */
class HtmlWidgetEnd extends HtmlBase {

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
		return $this->htmlOptions;
	}

	protected function openTag() {}

	protected function closeTag() {
		HtmlWidgetBegin::$_boxes--;
		parent::closeTag();
	}
}
