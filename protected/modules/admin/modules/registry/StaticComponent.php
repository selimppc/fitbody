<?php
/**
 * Created by JetBrains PhpStorm.
 * @property bool $isExistsNew
*/
class StaticComponent extends CDbMigration {

	public function init() {}
	/**
	 *
	 * нужно ли сохранять историю изменений
	 * @var bool
	 */
	public $supportHistory = false;

	/**
	 * id роли, с которой пользователь может редактировать (удалять и добавлять) папки и элементы
	 * а также применять миграции
	 * @var int
	 */
	public $accessLevelEdit = 1024;

	/**
	 * создавать ли миграции при каких-либо изменениях
	 * на стадии разработки лучше установить флаг в falce, если не нужна поддержка миграций
	 *
	 * @var bool
	 */
	public $createMigrations = true;

	public function getIsExistsNew()
	{
		$files = scandir(DOCUMENT_ROOT.'protected/migrations/registry/');
		foreach($files as $name) {
			if($name != '.' && $name != '..' && $name != 'template.php') {
				if(Migrations::model()->find('version = :version', array(':version' => preg_replace('/\.php$/','',$name))) === null)
					return true;
			}
		}
		return false;
	}


	public function getEditHtml($path, $return = false)
	{
		$this->accessLevelEdit = 50000;
		Yii::import('application.modules.admin.modules.registry.models.*');
		if(!$id = Registry::model()->getCurrentFolderId($path,true))
			return '';
		$element = Registry::model()->findByPk($id);
		if($element === null)
			return '';


		$cs = Yii::app()->clientScript;


		$html = '<div class="span5 well registry" data-action="/admin/registry/data/folder'.(($path[0]=='/')?'':'/').(($element->type != 'folder')?preg_replace('/\/[A-z0-9]*$/','',$path):$path).'?r='.Yii::app()->request->requestUri.'">';
		if(count(Yii::app()->params['languages'])>1) {
			$html .= Yii::app()->controller->renderPartial('application.modules.admin.modules.registry.views.data._language_select',array(
				'currentLanguage' => Yii::app()->language,
				'folderId' => ($element->type != 'folder') ? $element->parent_category_id : $element->id
			),true);
		}
		if($element->type != 'folder') {
			$html .= $this->getElement($element);
		} else {
			$elements = Registry::model()->findAll('parent_category_id = :id', array(':id' => $element->id));
			foreach($elements as $element) {
				if($element->type != 'folder')
					$html .= $this->getElement($element);
			}
		}

		$html .= '<div class="control-group">
					<a href="" class="save_registry btn btn-gebo" style="width:150px;">Save</a>
				</div>';
		$html .= '</div>';
		$cs->registerScript('registry',$this->getEditJs());
		if($return)
			return $html;
		else
			echo $html;
	}

	private function getElement($element)
	{
		if($element->type == 'redactor') {
			$cs = Yii::app()->clientScript;
			$cs->registerScriptFile('/js/redactor/redactor.js');
			$cs->registerCssFile('/js/redactor/redactor.css');
		}
		return  Yii::app()->controller->renderPartial('application.modules.admin.modules.registry.views.data.elements.'.$element->type,array(
			'element' => $element,
			'language' => Yii::app()->language
		),true);

	}

	public function getEditJs()
	{
		return <<<JS
		$('.registry .controls').css('width','100%').children().css('width','100%');
		$('[data-redactorin=true]').each(function () {
			$(this).redactor({
				minHeight: 200,
				buttons: ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|',
					'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
					'image', 'table', 'link', '|', 'alignment', '|', 'horizontalrule'],
				imageUpload: '/admin/registry/data/uploader.html?debug=0'
			});
		});

		$(document).on('change','#change_language',function (e) {
			changeRegistryLanguage($(this).data('folder-id'),$(this).val());
		});

		function changeRegistryLanguage(folderId,language) {
			$('[data-language]').hide(0, function () {
				$('div[data-language='+language+']').show(200);
			});
		}

		function saveRegistry(self) {
			var registryDiv = $(self).parents('.registry');
			$('body').append('<form style="display: none;" enctype="multipart/form-data" method="post" id="registry_form" action="'+registryDiv.data('action')+'"></form>');
			$('#registry_form').append($(registryDiv)).submit();
		}

		$(document).on('click','.save_registry', function (e) {
			e.preventDefault();
			saveRegistry($(this));
		});


JS;
	}

	public function makeMigrations()
	{
		$files = scandir(DOCUMENT_ROOT.'protected/migrations/registry/');
		foreach($files as $name) {
			if($name != '.' && $name != '..' && $name != 'template.php') {
				if(Migrations::model()->find('version = :version', array(':version' => preg_replace('/\.php$/','',$name))) === null) {
					$migration = new Migrations();
					$migration->version = preg_replace('/\.php$/','',$name);
					$migration->apply_time = time();
					$migration->save();
				}
			}
		}
		return true;
	}

	public function createUpdateOrAddMigration($modelName,$params)
	{
		if(!$this->createMigrations)
			return true;
		file_put_contents(DOCUMENT_ROOT.'protected/migrations/registry/template.php',$this->getTemplateForAdd($modelName,$params));
		$commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
		$runner = new CConsoleCommandRunner();
		$runner->addCommands($commandPath);
		$commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
		$runner->addCommands($commandPath);
		$args = array('yiic', 'migrate', 'create','registry_save'.md5(microtime()),'--interactive=0','--migrationPath=application.migrations.registry','--templateFile=application.migrations.registry.template');
		ob_start();
		$runner->run($args);
		$this->makeMigrations();
		file_put_contents(DOCUMENT_ROOT.'protected/migrations/registry/template.php','');
	}

	public function createDeleteMigration($modelName, $id)
	{
		if(!$this->createMigrations)
			return true;
		file_put_contents(DOCUMENT_ROOT.'protected/migrations/registry/template.php',$this->getTemplateForDelete($modelName,$id));
		$commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
		$runner = new CConsoleCommandRunner();
		$runner->addCommands($commandPath);
		$commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
		$runner->addCommands($commandPath);
		$args = array('yiic', 'migrate', 'create','registry_delete'.md5(microtime()),'--interactive=0','--migrationPath=application.migrations.registry','--templateFile=application.migrations.registry.template');
		ob_start();
		$runner->run($args);
		$this->makeMigrations();
		file_put_contents(DOCUMENT_ROOT.'protected/migrations/registry/template.php','');
	}

	public function migrate()
	{
		$commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
		$runner = new CConsoleCommandRunner();
		$runner->addCommands($commandPath);
		$commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
		$runner->addCommands($commandPath);
		$args = array('yiic', 'migrate','--interactive=0','--migrationPath=application.migrations.registry','--migrationTable=migrations');
		ob_start();
		$runner->run($args);
	}

	public function get($path,$data = array(),$language = null)
	{
		Yii::import('application.modules.admin.modules.registry.models.*');
		$id = Registry::model()->getCurrentFolderId($path);
		if($id === null)
			return '';
		$value = RegistryValue::model()->get($id,$language);
		return (count($data)) ? strtr($value,$data) : $value;
	}

	private function  getTemplateForAdd($modelName,$params) {
		$model = ($params['is_new']) ? 'new '.$modelName.'()' : $modelName.'::model()->findByPk('.$params['id'].')';
		unset($params['is_new']);
		$t = '<?php

class {ClassName} extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  '.$model.';
		';

		foreach($params as $key => $val) {
			$t .= '
		$model->'.$key.' = \''.preg_replace("/'/",'"',$val).'\'; ';
		}

		$t .= '
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "{ClassName} does not support migration down.\\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
';
		return $t;
	}

	private function  getTemplateForDelete($modelName,$id) {
		return '<?php

class {ClassName} extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		'.$modelName.'::model()->deleteByPk('.$id.',"",array(),false);
	}

	public function down()
	{
		echo "{ClassName} does not support migration down.\\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
';
	}


}