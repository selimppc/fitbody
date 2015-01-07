<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/6/12
 * Time: 12:12 PM
 * To change this template use File | Settings | File Templates.
 *
 * @property AdminModule module
 */
class BaseController extends Controller {

    //FIIIIIIIIxx
    public function init() {
        parent::init();
        Yii::app()->clientScript->scriptMap['jquery'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;
    }
	/**
	 * @var bool if is true client notification from user flash will be automatically displayed
	 * If is false than message will not showed
	 */
	public $clientNotifications = true;

	/**
	 * @var string client notificaion widget for show client notificaions
	 */
	public $clientNotificationWidget = 'admin.widgets.clientNotification.Sticky';

	public $actionClassView = 'admin.controllers.ViewAction';
	public $actionClassDisplay = 'admin.controllers.DisplayAction';
	public $actionClassEdit = 'admin.controllers.EditAction';

	public $assetUrl;
	public $layout = 'admin.views.layouts.index';

	protected function _includeLayoutHtml($view) {
		if (array_key_exists($view, $this->module->layoutIncludes)) {

		}
	}

	/**
	 * Set link title for items in top right menu
	 */
	protected function _configureNavRightMenu() {
		if (is_array($this->module->navRightMenuParams) && isset($this->module->navRightMenuParams['structure'])) {
			//  configure profile
			if (isset($this->module->navRightMenuParams['structure']['profile'])) {
				$this->module->navRightMenuParams['structure']['profile']['a']['title'] = Yii::app()->user->name;
			}
			//  configure language
			if (isset($this->module->navRightMenuParams['structure']['language'])) {
				if (isset($this->module->navRightMenuParams['structure']['language'])) {
					$systemLanguages = isset(Yii::app()->params['languages']) ? Yii::app()->params['languages']: array();
					$currentLanguage = Yii::app()->language;
					if (!empty($systemLanguages)) {
						$language = &$this->module->navRightMenuParams['structure']['language'];
						$language['a']['title'] = Yii::t('admin', 'Language');
						$language['a']['i'] = array(
							'class' => $systemLanguages[$currentLanguage]['icon']
						);
						$language['dropDown'] = array();
						foreach ($systemLanguages as $lang => $config) {
							if ($lang == $currentLanguage) {
								continue;
							}
							$language['dropDown'][$lang] = array(
								'a' => array(
									'title' => $config['title'],
									'href' => 'javascript:;',
									'onclick' => 'changeLanguage(\'' . $lang . '\')',
									'i' => array(
										'class' => $config['icon']
									)
								)
							);
						}
						Yii::app()->clientScript->registerPackage('language');
					}
				}
			}
		}
	}

	/**
	 * Include html source into layout
	 *
	 * @param string $view
	 *
	 * @return bool
	 */
	protected function beforeRender($view) {
		if ($this->layout == $view) {
			$this->_includeLayoutHtml($view);
		}
		return true;
	}

	/**
	 * Notify client using clientNotificationWidget with $options
	 *
	 * @param $message
	 * @param array $options
	 */
	protected function notifyClient($message, $options = array()) {
		$options['text'] = $message;
		$this->widget($this->clientNotificationWidget, $options);
	}

	/**
	 * Get user flashes and notify client
	 */
	public function notifyClientWithFlashNotifications() {
		static $isNotifyed = false;
		if ($isNotifyed) {
			return;
		}
		$isNotifyed = true;
		foreach (Yii::app()->user->getFlashes() AS $message) {
			$this->notifyClient($message);
		}
	}

	protected function beforeAction($action) {
		if (parent::beforeAction($action)) {
			$this->pageTitle = Yii::t('admin', 'Admin panel');
			$this->_configureNavRightMenu();
			return true;
		}
		return false;
	}

	public function actionChangeLanguage($lang = '') {
		if ($lang && isset(Yii::app()->params['languages'])) {
			if (array_key_exists($lang, Yii::app()->params['languages'])) {
				Yii::app()->setLanguage($lang);
				Yii::app()->session['_lang'] = $lang;
				echo 1;
			}
		}
		Yii::app()->end();
	}
}
