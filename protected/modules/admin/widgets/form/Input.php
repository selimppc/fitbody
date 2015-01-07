<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/23/12
 * Time: 5:12 PM
 * To change this template use File | Settings | File Templates.
 */
Yii::import('admin.widgets.form.BaseElement');
class Input extends BaseElement {

	/**
	 * Enter point
	 *
	 * @return mixed
	 */
	public function _run() {
		if ($this->model) {
			echo Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
		} else {
			echo Html::textField($this->attribute, $this->value, $this->htmlOptions);
		}
	}
}
