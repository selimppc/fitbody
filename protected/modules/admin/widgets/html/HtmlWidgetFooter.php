<?php
//  include superclass
Yii::import('admin.widgets.html.HtmlBase');
Yii::import('admin.widgets.html.HtmlWidgetEnd');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 2:04 PM
 * To change this template use File | Settings | File Templates.
 */
class HtmlWidgetFooter extends HtmlBase {

	public $end = false;

	public $class = 'w-box-footer';

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
		return $this->htmlOptions;
	}

	public function afterRun() {
		if ($this->end) {
			$this->endWidget($this->htmlWidgetMap['widgetEnd'], array());
		}
	}
}