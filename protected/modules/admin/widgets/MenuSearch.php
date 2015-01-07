<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/9/12
 * Time: 4:41 PM
 * To change this template use File | Settings | File Templates.
 */
class MenuSearch extends CWidget {

	public $action;
	public $method;
	public $htmlOptions = array(
		'class' => 'input-append'
	);

	public function run() {
		echo CHtml::beginForm($this->action, $this->method, $this->htmlOptions);
		echo CHtml::textField('searchString', '', array(
			'class' => 'search_query input-medium',
			'size' => 16,
			'placeholder' => Yii::t('admin', 'Search...')
		));
		echo CHtml::htmlButton('<i class="icon-search"></i>', array(
			'type' => 'submit',
			'class' => 'btn'
		));
		echo CHtml::endForm();
	}
}
