<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 15.10.13
 * Time: 17:12
 * To change this template use File | Settings | File Templates.
 */
class GridPopup extends CWidget {

	/**
	 * grid id
	 * @var int
	 */
	public $id;

	/**
	 * If $command not defined system will be use $model for constructing
	 * command
	 *
	 * @var CActiveRecord
	 */
	public $model;

	/**
	 * url for update
	 * @var string
	 */
	public $addUri;

	/**
	 * @var array grid column configuration
	 */
	public $columns;

	public $toRequest = array();

	public function init() {

	}

	public function run() {
		$this->render('admin.widgets.views.grid.popup');
	}

	public function getFields(CActiveForm $form) {
		if(!is_array($this->columns))
			return '';
		$html = '';
		foreach($this->columns as $column) {
			if(isset($column->editable) && $this->model->isAttributeSafe($column->name) && (!array_key_exists('enabled', $column->editable) || $column->editable['enabled'] === true)) {
				$html .= '<div class="control-group">';
				$html .= $form->label($this->model,$column->name);
				$html .= $this->getField($column, $form);
				$html .= '<br>'.$form->error($this->model,$column->name,array('class'=>'label label-important label-error-msg'));
				$html .= '</div>';
			}
		}
		foreach($this->toRequest as $key => $val) {
			$html .= $form->hiddenField($this->model,$key,array('value'=>$val));
		}
		return $html;
	}

	private function getField($column,CActiveForm $form) {
		$type = isset($column->editable['type']) ? $column->editable['type'] : 'text';
		switch ($type) {
			case 'text':
				return $form->textField($this->model,$column->name);
			case 'textarea':
				return $form->textArea($this->model,$column->name);
			case 'select':
				if(!isset($column->editable['source']) || !is_array($column->editable['source']))
					$column->editable['source'] = array();
				return $form->dropDownList($this->model, $column->name, $column->editable['source']);
			case 'date':
				return $form->dateField($this->model,$column->name);
		}
	}
}