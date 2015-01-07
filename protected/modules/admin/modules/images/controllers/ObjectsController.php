<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 07.10.13
 * Time: 16:11
 * To change this template use File | Settings | File Templates.
 */
class ObjectsController extends AdminController {

	public function actionAddSize() {
		if(isset($_POST['pk'])) {
			Yii::import('ext.editable.EditableSaver');
			$es = new EditableSaver('ImageSize');
			$es->update();
			die;
		}
		if(isset($_POST['ImageSize'])) {
			$model = new ImageSize('insert');
			$model->attributes = $_POST['ImageSize'];
			if($model->validate() && $model->save(false)) {
				echo true;
			} else {
				throw new CHttpException(400,'Data not saved: Invalid data');
			}
		}
	}

	public function actionUpdate() {
		if (Yii::app()->request->isAjaxRequest) {
			if (isset($_POST['ajax'])) {
				if (!isset($_POST['rt']) || $_POST['rt'] != 'deleteSelected') {
					return;
				}
				$result = array(
					'error' => true,
					'errorMsg' => 'Unknown error'
				);
				if (isset($_POST['ids'])) {
					$ids = CJavaScript::jsonDecode($_POST['ids']);
					if (is_array($ids)) {
						if (ImageSize::model()->deleteByPk($ids)) {
							unset($result['error']);
							unset($result['errorMsg']);
						}
					}
				}
				$result = CJSON::encode($result);
				echo $result;
				Yii::app()->end();
			}
		}
		Yii::import('admin.actions.UpdateAction');
		$action = new UpdateAction($this,'update');
		$action->modelName = 'ImageObject';
		$action->run();
	}

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'editable' => false,
				'actions' => array(
					'add','edit','delete'
				),
				'class' => 'admin.actions.ListAction',
				'caption' => 'Images',
				'modelName' => 'ImageObject',
				'multiple' => false,
				'columns' => array(
					array(
						'name' => 'id',
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 50
						),
					),
					array(
						'name'  => 'title',
					),
					array(
						'name'  => 'key'
					),
					array(
						'name'  => 'path'
					)
				),
			),
			'add' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'ImageObject',
			),
		));
	}
}