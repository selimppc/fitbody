<?php

class FrontController extends Controller {

	protected $isError = false;
    public $isGuest = true;
    protected $_rootCategoriesCoach;
    protected $_rootCategoriesProgram;
    protected $_rootCategoriesArticle;
    protected $_rootCategoriesExercise;
    protected $_rootDestinationsClub;
    protected $_rootCategoriesShop;

    public function disableProfilers() {
        if (Yii::app()->getComponent('log')) {
            foreach (Yii::app()->getComponent('log')->routes as $route) {
                if (in_array(get_class($route), array('CProfileLogRoute', 'CWebLogRoute', 'YiiDebugToolbarRoute','DbProfileLogRoute'))) {
                    $route->enabled = false;
                }
            }
        }
    }

    public function getRootCategoriesCoach() {
        if ($this->_rootCategoriesCoach === null) {
            $this->_rootCategoriesCoach = CoachCategory::model()->fetchRootCategories();
        }
        return $this->_rootCategoriesCoach;
    }

    public function getRootCategoriesProgram() {
        if ($this->_rootCategoriesProgram === null) {
            $this->_rootCategoriesProgram = ProgramCategory::model()->fetchRootCategories();
        }
        return $this->_rootCategoriesProgram;
    }

    public function getRootCategoriesArticle() {
        if ($this->_rootCategoriesArticle === null) {
            $this->_rootCategoriesArticle = ArticleCategory::model()->fetchRootCategories();
        }
        return $this->_rootCategoriesArticle;
    }

    public function getRootCategoriesExercise() {
        if ($this->_rootCategoriesExercise === null) {
            $this->_rootCategoriesExercise = Muscle::model()->fetchMuscles();
        }
        return $this->_rootCategoriesExercise;
    }

    public function getRootDestinationsClub() {
        if ($this->_rootDestinationsClub === null) {
            $this->_rootDestinationsClub = ClubDestination::model()->fetchRootDestinations();
        }
        return $this->_rootDestinationsClub;
    }

    public function getRootCategoriesShop() {
        if ($this->_rootCategoriesShop === null) {
            $this->_rootCategoriesShop = ShopCategory::model()->fetchRootCategories();
        }
        return $this->_rootCategoriesShop;
    }

	public function init() {
		if(Yii::app()->errorHandler===null) {
			$seo = Seo::model()->seoData;
			$this->pageTitle = $seo['title'];
			Yii::app()->clientScript->registerMetaTag($seo['description'],'description');
			Yii::app()->clientScript->registerMetaTag($seo['keywords'],'keywords');
		}
        $this->isGuest = Yii::app()->user->isGuest;
        $this->checkForumConnection();
	}

    protected function checkForumConnection() {
        if ($this->isGuest && Yii::app()->phpBB->loggedin() === 'SUCCESS') {
            Yii::app()->phpBB->logout();
        }

        if (!$this->isGuest && Yii::app()->phpBB->loggedin() === 'FAIL') {
            Yii::app()->phpBB->login(Yii::app()->user->getState('nickname'), Yii::app()->user->getState('forum_hash_password'));
        }
    }

    public function beforeRender($view) {

        if(Yii::app()->user->getFlash('login') || Yii::app()->session->get('loginFromForum')) {
            if (Yii::app()->session->get('loginFromForum')) {
                Yii::app()->session->remove('loginFromForum');
            }
            Yii::app()->clientScript->registerScript('redirect_to_login','$(function(){ $(document).trigger(\'show.loginPopup\'); })', CClientScript::POS_END);
        }

        Yii::app()->clientScript
            ->registerScriptFile('/js/ajax-connector.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/observer.js', CClientScript::POS_HEAD)

            ->registerScriptFile('/js/front/common.js', CClientScript::POS_HEAD)

            ->registerCssFile('/css/reset.css')
            ->registerCssFile('/css/clearfix.css')
            ->registerCssFile('/css/font.css')
            ->registerCssFile('/css/core.css')

            ->registerCssFile('/js/front/formstyler/jquery.formstyler.css')
            ->registerScriptFile('/js/front/formstyler/jquery.formstyler.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/formstyler/styler_init.js', CClientScript::POS_HEAD)

            ->registerScriptFile('/js/front/owl/owl.carousel.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/owl/owl.carousel.css')
            ->registerCssFile('/js/front/owl/owl.theme.css')
            ->registerScriptFile('/js/front/popup.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/app.js', CClientScript::POS_HEAD);

        Yii::app()->clientScript->scriptMap['jquery.js'] = '/js/front/jquery-1.10.min.js';
        Yii::app()->clientScript->scriptMap['jquery.min.js'] = '/js/front/jquery-1.10.min.js';
        Yii::app()->clientScript->registerCoreScript('cookie');
        Yii::app()->clientScript->registerCoreScript('jquery');

        return parent::beforeRender($view);
    }

	// render function
	public function render($view,$data=null,$return=false,$fullPath=false) {
		if(!$fullPath) {
			$view = '//content/'.$view;
		}
		parent::render($view,$data,$return);
	}

    public function redirectToLogin() {
        Yii::app()->user->setFlash('login',true);
        Yii::app()->controller->redirect('/');
    }

}