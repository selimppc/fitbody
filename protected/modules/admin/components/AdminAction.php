<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 22.09.13
 * Time: 19:02
 * To change this template use File | Settings | File Templates.
 */
abstract class AdminAction extends CAction {

	private $_modelName;
	private $_view;

	/**
	 * Упрощенная переадресация по действиям контроллера
	 * По-умолчанию переходим на основное действие контроллера
	 */
	public function redirect($actionId = null) {
		if ($actionId === null)
			$actionId = $this->controller->defaultAction;

		$this->controller->redirect(array($actionId));
	}

	/**
	 * Инитим вьюху.
	 */
	public function render($data, $return = false) {
		if ($this->_view === null)
			$this->_view = $this->id;

		return $this->controller->render($this->_view, $data, $return);
	}

	/**
	 * Возвращаем новую модель или пытаемся найти ранее
	 * созданную запись, если известен id
	 */
	public function getModel($scenario = 'insert') {
		if (($id = Yii::app()->request->getParam('id')) === null)
			$model = new $this->modelName($scenario);
		else if (($model = CActiveRecord::model($this->modelName)->resetScope()->findByPk($id)) === null)
			throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
		return $model;
	}

	/**
	 * Возвращает имя модели, с которой работает контроллер
	 * По-умолчанию имя модели совпадает с именем контроллера
	 */
	public function getModelName() {
		if ($this->_modelName === null)
			$this->_modelName = ucfirst($this->controller->id);

		return $this->_modelName;
	}

	public function setView($value) {
		$this->_view = $value;
	}

	public function getView() {
		return $this->_view;
	}

	public function setModelName($value) {
		$this->_modelName = $value;
	}

	public function getIsEdit() {
		return Yii::app()->request->getParam('id',false);
	}
}