<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/20/12
 * Time: 12:25 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Javascript notification system based on sticky notifications
 */
class Sticky extends BaseWidget {

	/**
	 * @var string meesage for show
	 */
	public $text;

	/**
	 * @var int miliseconds before closing message
	 * If false message shoold be closed manually
	 */
	public $autoclose = 5000;

	/**
	 * @var string position of message
	 * @default top-right
	 *
	 * Can be:
	 *      - top-left
	 *      - top-right
	 *      - bottom-left
	 *      - bottom-right
	 */
	public $position = 'top-right';

	/**
	 * @var string animation speed
	 *
	 * Can be:
	 *      - fast
	 *      - slow
	 *      - integer miliseconds
	 *
	 * @default slow
	 */
	public $speed = 'slow';

	/**
	 * @var bool if true messages can dublicates
	 */
	public $duplicates = true;

	/**
	 * @var string define message type
	 *
	 * Can be:
	 *      - st-success
	 *      - st-error
	 *      - st-info
	 * @default null usual notification
	 */
	public $type;

	/**
	 * Inlcude package
	 */
	public function init() {
		Yii::app()->clientScript->registerPackage('sticky_notifications');
	}

	public function run() {
		$speed = is_string($this->speed) ? sprintf('"%s"', $this->speed) : (int)$this->speed;
		$this->text = addcslashes($this->text, '"');
		$type = $this->type ? sprintf(', type: "%s"', $this->type) : '';
		$script =<<<SCRIPT
$.sticky("{$this->text}", {autoclose : {$this->autoclose}, position: "{$this->position}", speed: {$speed}, duplicates: {$this->duplicates} {$type} });
SCRIPT;
		Yii::app()->clientScript->registerScript($this->getId(), $script);
	}
}
