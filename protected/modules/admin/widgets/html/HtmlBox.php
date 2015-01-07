<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 12:15 PM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Row divider
 *
 * Can divide html on 12 parts
 *
 * For set box size use
 * <pre>
 *      <?php $this->beginWidget('admin.widgets.RowBox', array('size' => 4)); ?>
 *          ... you code here ...
 *      <?php $this->endWidget('admin.widgets.RowBox'); ?>
 * </pre>
 */
class HtmlBox extends HtmlBase {

	/**
	 * @var string
	 */
	public $class = 'span';

	/**
	 * Box size
	 *
	 * Min: 1
	 * Max: 12 (if size more than 12, page will be have horizontall scroll)
	 *
	 * @var int
	 */
	public $size = 12;

	/**
	 * @var bool if is true div will be contain sortable class and if you use widget you can sort widgets
	 */
	public $sortable = false;

	/**
	 * @var string
	 */
	public $sortableClass = 'ui-sortable';

	/**
	 * Additional class to box
	 *
	 * Will be <div class="span12 {addClass}">
	 *
	 * @var string
	 */
	public $addClass = '';

	protected function tag() {
		return 'div';
	}

	protected function tagOptions() {
		parent::addClass($this->class . $this->size);
		parent::addClass($this->addClass);
		if ($this->sortable) {
			parent::addClass($this->sortableClass);
		}
		return $this->htmlOptions;
	}
}