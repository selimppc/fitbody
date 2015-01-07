<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 12:49 PM
 * To change this template use File | Settings | File Templates.
 */
abstract class HtmlBase extends BaseWidget {

	public $htmlWidgetMap = array(
		'row' => 'admin.widgets.html.HtmlRow',
		'box' => 'admin.widgets.html.HtmlBox',
		'header' => array(
			'admin.widgets.html.HtmlWidgetBegin',
			'admin.widgets.html.HtmlWidgetHeader'
		),
		'content' => 'admin.widgets.html.HtmlWidgetContent',
		'widgetBegin' => 'admin.widgets.html.HtmlWidgetBegin',
		'widgetEnd' => 'admin.widgets.html.HtmlWidgetEnd',
		'footer' => array(
			'admin.widgets.html.HtmlWidgetFooter',
			'admin.widgets.html.HtmlWidgetEnd'
		)
	);

	public $rowClass = '';
	public $rowBoxClass = 'admin.widgets.html.RowBox';
	public $rowBoxHeaderClass = 'admin.widgets.html.RowBoxHeader';
	public $rowBoxContentClass = 'admin.widgets.html.RowBoxHeader';

	public $htmlOptions = array();

	/**
	 * You can wrap content more than one widget at once
	 *
	 * key => widget class or valid key from $htmlWidgetMap array, string
	 * value => widget configuration, array
	 *
	 * @var array
	 */
	public $widgets;

	/**
	 * Must return tag name
	 *
	 * @example div, li, ul
	 *
	 * @return string
	 */
	abstract protected function tag();

	/**
	 * Must return html options
	 *
	 * @see CHtml::openTag
	 *
	 * @return array
	 */
	abstract protected function tagOptions();

	/**
	 * Add class property to html options
	 *
	 * @param $class
	 */
	protected function addClass($class) {
		if ($class) {
			if (!isset($this->htmlOptions['class'])) {
				$this->htmlOptions['class'] = $class;
			} else if (strstr($this->htmlOptions['class'], $class) === false) {
				$this->htmlOptions['class'] .= ' ' . $class;
			}
		}
	}

	/**
	 * Draw start tag
	 */
	protected function openTag() {
		echo CHtml::openTag($this->tag(), $this->tagOptions());
	}

	/**
	 * Draw close tag
	 */
	protected function closeTag() {
		echo CHtml::closeTag($this->tag());
	}


	public function beforeInit() {}

	/**
	 * Initialize additional widgets
	 */
	public function afterInit() {
		if (!empty($this->widgets)) {
			foreach ($this->widgets as $mapKey => $config) {
				if (array_key_exists($mapKey, $this->htmlWidgetMap)) {
					$mapClasses = (array) $this->htmlWidgetMap[$mapKey];
					foreach ($mapClasses as $class) {
						$this->beginWidget($class, $config);
					}
				} else {
					$this->beginWidget($mapKey, $config);
				}
			}
		}
	}

	/**
	 * End additional widgets
	 */
	public function beforeRun() {
		if (!empty($this->widgets)) {
			foreach ($this->widgets as $mapKey => $config) {
				if (array_key_exists($mapKey, $this->htmlWidgetMap)) {
					$mapClasses = (array) $this->htmlWidgetMap[$mapKey];
					foreach ($mapClasses as $class) {
						$this->endWidget($class);
					}
				} else {
					$this->endWidget($mapKey);
				}
			}
		}
	}

	public function afterRun() {}

	public function init() {
		$this->beforeInit();
		$this->openTag();
		$this->afterInit();
	}

	public function run() {
		$this->beforeRun();
		$this->closeTag();
		$this->afterRun();
	}
}
