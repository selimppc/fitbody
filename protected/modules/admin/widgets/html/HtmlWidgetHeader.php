<?php
//  include superclass
Yii::import('admin.widgets.html.HtmlBase');
Yii::import('admin.widgets.html.HtmlWidgetBegin');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 12:46 PM
 * To change this template use File | Settings | File Templates.
 */
class HtmlWidgetHeader extends HtmlBase {

	/**
	 * @var bool
	 */
	public $begin = false;

	public $class = 'w-box-header';

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

	public function beforeInit() {
		if ($this->begin) {
			$this->beginWidget($this->htmlWidgetMap['widgetBegin'], array());
		}
	}
}
