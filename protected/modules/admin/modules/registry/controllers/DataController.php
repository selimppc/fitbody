<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:46 PM
 * To change this template use File | Settings | File Templates.
 */
class DataController extends RegistryController implements IAjax {

	private $folderForm;

	public function init()
	{
		parent::init();
		Yii::import('admin.modules.registry.models.*');
	}

	public function beforeAction($action)
	{
		$this->folderForm = new FolderForm();
		if(isset($_POST['FolderForm'])) {
			$this->folderForm->attributes = $_POST['FolderForm'];
			if($this->folderForm->save()) {
				parent::redirect($this->createUrl('/admin/registry/data/folder'.Registry::model()->getFullPathById($this->folderForm->id)));
			}
		}
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile('/js/redactor/redactor.js');
		$cs->registerCssFile('/js/redactor/redactor.css');
		return parent::beforeAction($action);
	}

	public function actionIndex()
	{
		$this->render('index', array(
			'tree' => Registry::model()->getFolders(),
			'folderForm' => $this->folderForm
		));
	}

	public function actionDelete($id, $r)
	{
		if(Yii::app()->user->role == 1024) {
			Registry::model()->deleteByPk($id);
		}
		parent::redirect($r);
	}

	public function actionFolder($r = null)
	{
		$elementForm = new ElementForm();
		if(isset($_POST['ElementForm'])) {
			$elementForm->attributes = $_POST['ElementForm'];
			if($elementForm->save())
				$elementForm = new ElementForm();
		}

		if(isset($_POST['elementData'])) {
			$this->saveValue();
		}

		if($r !== null)
			parent::redirect($r);

		$folderId = Registry::model()->getCurrentFolderId();
		$elements = Registry::model()->getElements($folderId);
		$this->render('folder', array(
			'tree' => Registry::model()->getFolders($folderId),
			'elements' => $elements,
			'folderForm' => $this->folderForm,
			'folderId' => $folderId,
			'elementForm' => $elementForm
		));
	}

	private function saveValue()
	{
		$createDate = date('Y-m-d H:i:s');
		foreach($_POST['elementData'] as $id => $data) {
			$registry = Registry::model()->findByPk($id);
			foreach($data as $lang => $val) {
				if($registry->type == 'image') {
					$key = 'elementData_'.$id.'_'.$lang;
					if($_FILES[$key]['size']) {
						$val = Yii::app()->image->save($key,'registry');
					}
				}
				$value = new RegistryValue();
				$value->registry_id = $id;
				$value->language = $lang;
				$value->value = preg_replace("/'/",'"',$val);
				$value->old = 0;
				$value->create_date = $createDate;
				$value->save();
			}
		}
	}

	public function actionUploader()
	{
		$redactorFiles = DOCUMENT_ROOT.'public_html/redactor/';
		$file = $redactorFiles.$_FILES['file']['name'];
		Yii::app()->files->makedir($redactorFiles);
		copy($_FILES['file']['tmp_name'],$file);
		$array = array(
			'filelink' => '/redactor/'.$_FILES['file']['name']
		);
		echo stripslashes(json_encode($array));
		Yii::app()->end();
	}

	public function getSuccess()
	{
		return array(
			'delete' => UserRole::USER_ROLE_MANAGER,
			'changeLanguage' => UserRole::USER_ROLE_MANAGER
		);
	}

	public function delete($id,$url)
	{
		$folderId = Registry::model()->getCurrentFolderId($url,true);
		$parentsId = Registry::model()->getParentIds($folderId);
		$data = Registry::model()->findByPk($id);
		if($data===null)
			throw new CException('Folder not exists');
		Registry::model()->deleteByPk($id);
		if(!in_array($id,$parentsId))
			return $url;
		elseif($data->parent_category_id !== null)
			return $this->createUrl('/admin/registry/data/folder'.Registry::model()->getFullPathById($data->parent_category_id));
		else
			return $this->createUrl('/admin/registry/data');
	}

	public function changeLanguage($id, $lang)
	{
		if(!isset($_SESSION['registry_lang']))
			$_SESSION['registry_lang'] = array();
		$_SESSION['registry_lang'][$id] = $lang;
		return true;
	}
}