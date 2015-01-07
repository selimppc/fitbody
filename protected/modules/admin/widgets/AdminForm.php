<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/20/12
 * Time: 1:23 PM
 * To change this template use File | Settings | File Templates.
 */
class AdminForm extends CActiveForm {

	public function input($model,$attribute,$htmlOptions=array()) {
		return parent::textField($model,$attribute,$htmlOptions);
	}
}
