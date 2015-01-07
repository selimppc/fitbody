<?php
class DefaultController extends BaseController {

	public function actionLogin() {
		if(!Yii::app()->user->isGuest) {
			$url = Yii::app()->user->returnUrl;
			if (!$url || $url == '/') {
				$url = $this->module->defaultUrl;
			}
			$this->redirect($url);
		}
		$loginForm = new LoginForm();
		if (isset($_POST['LoginForm'])) {
			$loginForm->attributes = $_POST['LoginForm'];
			if ($loginForm->validate() && $loginForm->login()) {
				$url = Yii::app()->user->returnUrl;
				if (!$url || $url == '/') {
					$url = $this->module->defaultUrl;
				}
				$this->redirect($url);
			}
		}
		$this->layout = 'login';
		$this->render('login', array(
			'model' => $loginForm
		));
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect($this->createUrl('login'));
	}

	public function actionIndex() {
		$this->render('index');
	}
}