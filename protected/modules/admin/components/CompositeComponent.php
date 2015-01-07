<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/9/12
 * Time: 1:05 PM
 * To change this template use File | Settings | File Templates.
 *
 * Composite pattern
 *
 * @see http://en.wikipedia.org/wiki/Composite_pattern
 *
 */
abstract class CompositeComponent {

	protected $id;
	protected $params;

	/**
	 * @param $id
	 * @param array $params
	 */
	public function __construct($id, $params = array()) {
		$this->id = $id;
		$this->params = $params;
	}

	/**
	 * @return mixed
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @param CompositeComponent $component
	 *
	 * @return mixed
	 */
	abstract public function add(CompositeComponent $component);

	/**
	 * @param CompositeComponent $component
	 *
	 * @return mixed
	 */
	abstract public function remove(CompositeComponent $component);

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	abstract public function get($id);
}
