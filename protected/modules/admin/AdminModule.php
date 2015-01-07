<?php
/**
 * Base admin module
 *
 * ALL PARENT MODULES SHOOLD EXTEND admin MODULE
 */
class AdminModule extends CWebModule {

	public $icon = 'icon-folder-close';

	/**
	 * @var array
	 */
	protected $_moduleConfig;

	/**
	 * @var bool
	 */
	protected static $moduleInited = false;

	/**
	 * Filename of packages
	 *
	 * @var string
	 */
	public $registerDefaultPackagesFile = 'packages.php';

	/**
	 * @var string
	 */
	public $registerModulePackagesFile = 'packages.php';

	/**
	 * @var string
	 */
	public $assetUrl;

	public $title = '';

	/**
	 * Module structure that will be displayed in left menu
	 * @example
	 *   array(
	 *      //'header' => true  //  is this flag was uncomment and will be set to true, item into menu will not be contain a link
	 *      // instead it will be displayed title as logical block header
	 *      'title' => 'User',          //  string will be translated
	 *      'url' => '',                //
	 *      'allowedRoles' => array(),  //  allowed
	 *      'events' => array()
	 *      'exceptRoles' => array()    //  except
	 *   )
	 *
	 * @var array
	 */
	public $menuItems = array();

	/**
	 * @var string
	 */
	public $navMenuClass = 'admin.widgets.NavMenu';

	/**
	 *
	 * For set this option please read first NavMenu documentation
	 * NavMenu location: admin.widget.NavMenu
	 *
	 * @var array
	 */
	public $navMenuParams = array();

	/**
	 * Widget will be displayed in top right
	 *
	 * @var string
	 */
	public $navRightMenuClass = 'admin.widgets.NavRightMenu';

	/**
	 * Params:
	 *
	 * structure - list of assoc arrays or key => array()
	 * example:
	 *      array(
	 *          //  1.
	 *          'settings' => array(
	 *              'li' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions',
	 *              'a' => array(
	 *                  //  valid htmlOptions will be used in CHtml::beginTag as htmlOptions
	 *                  //  as key => value
	 *
	 *                  //  you can use createUrl instead of href
	 *                  //  'createUrl' => true
	 *
	 *                  //
	 *                  //  Additional options
	 *                  //
	 *                  //  you can use assetUrl in img 'assetUrl' => true,
	 *                  img' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions, img will be pasted into a',     //  example output: <img src="" alt="" class="user_avatar"/>
	 *                  //  OR
	 *                  'i' => 'array: valid htmlOptions will be used in CHtml::tag as htmlOptions, i will be pasted into a',     //  example output: <i class="flag-ru"></i>
	 *                  //  in once time please use img or i
	 *              ),
	 *              //  can be not defined or empty
	 *              'dropDown' => Must return list of assoc arrays which following structure (can repeat structure above):
	 *                  array(
	 *                      'li' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions'
	 *                      'a' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions',    //  example output: <li><a href="javascrip:void(0)">Another action</a></li>
	 *                      'divider' => true   //  bool property, default (and if not defined) false, if exists and true will be used next code: <li class="divider"></li>, and link options will be skipped
	 *                  )
	 *              )
	 *          ),
	 *          //  OR
	 *          //  2.
	 *          array(
	 *              'li' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions',
	 *              'a' => array(
	 *                  //  valid htmlOptions will be used in CHtml::beginTag as htmlOptions
	 *                  //  as key => value
	 *
	 *                  //  you can use createUrl instead of href
	 *                  //  'createUrl' => true
	 *
	 *                  //
	 *                  //  Additional options
	 *                  //
	 *                  //  you can use assetUrl in img 'assetUrl' => true,
	 *                  img' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions, img will be pasted into a',     //  example output: <img src="" alt="" class="user_avatar"/>
	 *                  //  OR
	 *                  'i' => 'array: valid htmlOptions will be used in CHtml::tag as htmlOptions, i will be pasted into a',     //  example output: <i class="flag-ru"></i>
	 *                  //  in once time please use img or i
	 *              ),
	 *              //  can be not defined or empty
	 *              'dropDown' => Must return list of assoc arrays which following structure (can repeat structure above):
	 *                  array(
	 *                      'li' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions'
	 *                      'a' => 'array: valid htmlOptions will be used in CHtml::beginTag as htmlOptions',    //  example output: <li><a href="javascrip:void(0)">Another action</a></li>
	 *                      'divider' => true   //  bool property, default (and if not defined) false, if exists and true will be used next code: <li class="divider"></li>, and link options will be skipped
	 *                  )
	 *          )
	 *      )
	 *
	 * @var array
	 */
	public $navRightMenuParams = array();

	/**
	 * You can include any html into any layout
	 *
	 * key -> layot name, example: admin.views.layouts.index
	 * value -> html file name: admin.views.popup.html
	 *
	 * @var array
	 */
	public $layoutIncludes = array();

	/**
	 * List of widgets that will be inited and displayed in left menu
	 *
	 * @var array
	 */
	public $sideBarWidgets = array();

	/**
	 * List of widgets that will be inited and displayed in content area (controller view) in the top
	 *
	 * @var array
	 */
	public $contentTopWidgets = array();

	/**
	 * Default redirect url
	 * Will be used after user login if return url not defined
	 *
	 * @var string
	 */
	public $defaultUrl = '/admin';

	/**
	 *
	 *
	 * @var array
	 */
	public $allowedRoles = array(
		'255',
		'1024'
	);

	private function _importAdminBaseClasses() {
		$this->setImport(array(
			'admin.helpers.Html',
			'admin.widgets.grid.*',
			'admin.widgets.AdminForm',
			'admin.widgets.html.*',
			'ext.editable.*'
		));
	}

	protected function _configurePackages(array $config) {
		foreach ($config['packages'] as $name => $package) {
			$this->_setPackage($name, $package);
		}
		foreach ($config['register'] as $packageName) {
			$this->registerPackage($packageName);
		}
	}

	/**
	 * Register admin module packages
	 *
	 * Default Packages locates in admin module directory in file packages.php
	 */
	protected function _configureBasePackages() {
		$this->registerDefaultPackagesFile = Yii::getPathOfAlias('admin') . '/' . $this->registerDefaultPackagesFile;
		$config = require_once $this->registerDefaultPackagesFile;
		$this->_configurePackages($config);
	}

	/**
	 * Register module packages
	 */
	protected function _configureModulePackages() {
		if (Yii::app()->controller->getModule()->getId() == 'admin') {
			return;
		}
		if ($this->registerModulePackagesFile) {
			$packageFile = Yii::app()->controller->getModule()->getBasePath() . '/' . $this->registerModulePackagesFile;
			if (file_exists($packageFile)) {
				$config = require_once $packageFile;
				$this->_configurePackages($config);
			}
		}
	}

	protected function _setPackage($name, $package) {
		$package['baseUrl'] = $this->assetUrl . '/';
		Yii::app()->clientScript->packages[$name] = $package;
	}

	protected function _configureModule($config) {
		foreach ($config as $key => $value) {
			if (property_exists($this, $key)) {
				if (is_array($this->$key)) {
					$this->$key = CMap::mergeArray($this->$key, $value);
					continue;
				}
			}
			$this->$key = $value;
		}
	}

	/**
	 * Register package alias
	 *
	 * @param string $package
	 */
	public function registerPackage($package) {
		Yii::app()->clientScript->registerPackage($package);
	}

	/**
	 * Reimplement module config
	 *
	 * @param array $config
	 */
	public function configure($config) {
		if (is_array($config)) {
			if ($this->getId() == 'admin') {
				$this->_moduleConfig = $config;
				unset($this->_moduleConfig['modules']);
				$this->_configureModule($config);
			} else {
				$this->_configureModule(CMap::mergeArray(Yii::app()->getModule('admin')->_moduleConfig, $config));
			}
		}
	}

	public function init() {
		Yii::app()->getClientScript()->registerCoreScript('cookie');
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
			'admin.controllers.*',
		));
		if (isset(Yii::app()->session) && isset(Yii::app()->session['_lang'])) {
			Yii::app()->setLanguage(Yii::app()->session['_lang']);
		}
	}

	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			if (!self::$moduleInited) {
				$controller->assetUrl = $this->assetUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('admin.assets'));
				if (Yii::app()->user->isGuest) {
					if ($action->getId() !== 'login') {
						Yii::app()->user->setReturnUrl(Yii::app()->request->getRequestUri());
						$controller->redirect(Yii::app()->createUrl('admin/login'));
					}
				} else {
					if (!in_array(Yii::app()->user->role, $this->allowedRoles)) {
						throw new CHttpException(403, Yii::t('admin', 'You do not have permissions'));
					}
				}
				$this->_importAdminBaseClasses();
				$this->_configureBasePackages();
				$this->_configureModulePackages();
				self::$moduleInited = true;
			}

			return true;
		} else
			return false;
	}
}
