<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 22.09.13
 * Time: 19:04
 * To change this template use File | Settings | File Templates.
 */
class UpdateAction extends AdminAction {

	public $errors;

	public function run() {
		if($this->id == 'add' && $this->view === null) {
			$this->view = 'update';
		}

		$model = $this->getModel();

		if(Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
			Yii::import('ext.editable.EditableSaver');
			$es = new EditableSaver($this->modelName);
			$es->update();
			die;
		}

		$success = false;
		if(isset($_POST[$this->modelName])) {
			$model->attributes = $_POST[$this->modelName];
			if($model->validate() && $model->save(false)) {

				if(Yii::app()->request->isAjaxRequest) {
					echo true;
				} else {
					// была нажата кнопка Save And Close - сохраняем и переходим на List
					if(isset($_POST['save_and_close']) && $_POST['save_and_close'] == '') {
						$this->redirect();
					}
					// была нажата кнопка Save and Add new - сохраняем и перекидываем на action Add
					elseif(isset($_POST['save_and_add']) && $_POST['save_and_add'] == '') {
						$this->redirect('add');
					} else {
						$success = true;
					}
				}
			} else {
				$this->errors = $model->getErrors();
				if(Yii::app()->request->isAjaxRequest)
					throw new CHttpException(400,'Data not saved: Invalid data');
			}
		}
		if(Yii::app()->request->isAjaxRequest && $this->id == 'add')
			Yii::app()->end();

		$this->render(array(
			'model'     => $model,
			'errors'    => $this->errors,
			'listUri'   => $this->getListUri(),
			'success'   => $success
		));
	}

	public function getListUri() {
		return preg_replace(array('/\/$/','/\/update.*/','/\/add.*/'),'',Yii::app()->request->getRequestUri()).'/list';
	}
}