<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/23/12
 * Time: 5:08 PM
 * To change this template use File | Settings | File Templates.
 */
abstract class BaseElement extends BaseWidget {

	/**
	 * @var array list of packages what will auto registered
	 */
	protected $registerPackages = array();

	/**
	 * @var bool if is true, any widget output like (echo, print, print_r ...) will returned as string
	 * @default false
	 */
	public $return = false;

	/**
	 * @var string
	 */
	public $attribute;

	/**
	 * @var mixed
	 */
	public $value;

	/**
	 * @var CModel
	 */
	public $model;

	/**
	 * @var array
	 */
	public $htmlOptions = array();

	/**
	 * Register client packages
	 */
	public static function registerPackages() {

	}

	public function init() {
		parent::init();
		foreach ($this->registerPackages as $package) {
			Yii::app()->clientScript->registerPackage($package);
		}
	}

	/**
	 * Enter point
	 *
	 * @return mixed
	 */
	abstract public function _run();

	protected function beforeRun() {}

	protected function afterRun() {}

	public function run() {
		parent::run();
		if ($this->return) {
			ob_start();
		}
		$this->beforeRun();
		$this->_run();
		$this->afterRun();
		if ($this->return) {
			return ob_get_clean();
		}
	}
}
