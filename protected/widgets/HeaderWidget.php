<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/3/14
 * Time: 1:26 PM
 * To change this template use File | Settings | File Templates.
 */
class HeaderWidget extends CWidget {

	public function init() {}

	public function run(){
		$item = $this->controller->id;
		$this->render('header');
	}
}