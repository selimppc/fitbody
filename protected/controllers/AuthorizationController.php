<?php


class AuthorizationController extends FrontController {

    public function actionLogin() {

        if ($post = Yii::app()->request->getPost('LoginForm')) {
            if(isset($post['email']) && !(strpos($post['email'], '@') !== false)){
                if($tempUser = User::model()->findByAttributes(array('nickname' => $post['email']))){
                    $post['email'] = $tempUser->email;
                }
            }
            $redirect = (Yii::app()->request->getParam('redirect')) ? Yii::app()->request->getParam('redirect') : '/';

            $model = new LoginForm('login');
            $model->attributes = $post;
            $target = Yii::app()->request->getParam('target');

            $model->target = (in_array($target, array(LoginForm::TARGET_POPUP, LoginForm::TARGET_MENU))) ? $target : LoginForm::TARGET_MENU;

            $status = User::model()->findByAttributes(array('email' => $post['email']))->status;


            if($status == 2){

                $data = array('status'=>$status, 'message'=>'Account is not Activated yet!' );
                $this->renderJSON($data);

            }elseif(!$model->validate()){

                Yii::app()->user->setFlash('loginAttributes', $model->attributes);
                Yii::app()->user->setFlash('loginErrors', $model->getErrors());
                Yii::app()->user->setFlash('activeLoginTarget', $model->target);

                //$data = array('status'=>null, 'message'=>'Username or Password Invalid' );
                //$this->renderJSON($data);

            }else{

                $model->login();
                //$status = User::model()->findByAttributes(array('email' => $post['email']))->status;
                //$data = array('status'=>$status, 'message'=>'Login Successful!' );
                //$this->renderJSON($data);

            }

            $this->redirect($redirect);
        }
    }

    public function actionLoginByHash($hash) {
        $redirect = (Yii::app()->request->getParam('redirect')) ? Yii::app()->request->getParam('redirect') : '/';
        if ($hash) {
            $user = User::model()->find(array('condition' => 't.hash = :hash AND status = :status AND t.hash_created_at > NOW()', 'params' => array(':hash' => $hash, ':status' => User::STATUS_ACTIVE)));
            if(!$user){
                $this->redirect('/');
            }
            $model = new LoginForm();
            if ($model->login($hash)) {
                $user->hash = User::generateHash($user->email);
                $user->hash_created_at = User::generateTimeExpiration();
                $user->update();
                Yii::app()->user->setFlash('profilePassword', true);
                $this->redirect(urldecode($redirect));
            }

        }
    }


    public function actionRegister() {
        Yii::app()->clientScript
            ->registerCssFile('/css/jquery.jscrollpane.css')
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerCssFile('/js/front/ui/datepicker/datepicker.theme.css')
            ->registerScriptFile('/js/front/jquery.jscrollpane.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/jquery.mousewheel.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/register.js', CClientScript::POS_HEAD);

        $model = new User();
        $model->setScenario("register");

        $countries = Country::model()->findAll(array('condition' => 'status = :status', 'params' => array(':status' => Country::STATUS_ACTIVE)));

        if ($post = Yii::app()->request->getPost('User')) {
            $model->attributes = $post;
            $model->role_id = User::USER_USER;
            $model->last_login_date = date('Y-m-d H:i:s');
            $model->status = User::STATUS_INACTIVE;
            $model->attachEventHandler('onAfterRegister', array(new RegisterActivity(), 'addActivity'));
            $model->attachEventHandler('onAfterRegister', array('EmailNotificationManager', 'sendEmailRegisterUser'));

            $result = $this->actionCheckEmailNickname($model->email, $model->nickname);

            if($result ==1){

                $data = array('message'=>'Email or Nickname already exists !' );
                $this->renderJSON($data);

            }else{

                if ($model->save()) {
                    $data = array(
                        'first_name' => $model->first_name,
                        'last_name' => $model->last_name,
                        'email' => $model->email,
                        'nickname' => $model->nickname,
                        'password' => $post['password'],
                        'type' => 'normal',
                    );

                    //$this->renderJSON($data);
                    Yii::app()->user->setFlash('email', $model->email);
                    $this->redirect(array('authorization/thanks'));
                }else{
                    $data = array('message'=>'Invalid Request. Please try again !' );
                    $this->renderJSON($data);
                }
            }

        }
        $this->render('register', compact('model','countries'));
    }


    private function actionCheckEmailNickname($email, $nickname){
        $sql="SELECT 1 FROM user WHERE email='$email' OR nickname='$nickname' ";
        $cmd=Yii::app()->db->createCommand($sql);
        $result= $cmd -> queryScalar();
        return $result;
    }

    public function actionLogout() {
        //if(!Yii::app()->user->isGuest)
            Yii::app()->user->logout();

        parent::redirect('/');
    }

    public function actionThanks() {
        if (!$email = Yii::app()->user->getFlash('email')) {
            $this->redirect('/');
        }
        $this->render('thanks', compact('email'));
    }

    public function actionConfirmation($hash) {
        $user = User::model()->find(array('condition' => 't.hash = :hash', 'params' => array(':hash' => $hash)));
        if(!$user){
          throw new CHttpException(404, 'There is no such user.');
        }

        $model = new LoginForm();
        if ($model->login($hash)) {
            $user->status = 1;
            $user->hash = User::generateHash($user->email);
            $user->hash_created_at = User::generateTimeExpiration();
            if(!$user->update()) {
                throw new CHttpException(404, 'Error. Account is not activated.');
            }
            $this->render('confirmation');
        } else {
            $this->redirect('/');
        }
    }

    public function actionForgotPassword() {
        $model = new User('forgotPassword');
        if ($post = Yii::app()->request->getPost('User')) {
            $model->attributes = $post;
            $model->status = 1;
            if($model->validate()) {
                EmailNotificationManager::sendEmailForgotPassword($model->email);
                Yii::app()->user->setFlash('successSentInstructions', true);
                $this->redirect($this->createUrl('authorization/forgotPasswordInstructions'));
            }
        }

        $this->render('forgotPassword', compact('model'));
    }

    public function actionRedirectToLogin() {
        Yii::app()->user->setFlash('login', true);
        //$redirect = (Yii::app()->request->getParam('redirect')) ? Yii::app()->request->getParam('redirect') : '/';
        Yii::app()->controller->redirect('/');
    }

    public function actionForgotPasswordInstructions() {
        if(Yii::app()->user->getFlash('successSentInstructions')) {
            $this->render('forgotPasswordInstructions');
        }
        $this->redirect('/');
    }

    public function filters() {
        return array(
            'accessControl',
            'postOnly + login',
        );
    }

    public function accessRules() {
        return array(
//            array(
//                'deny', 'actions' => array('logout1'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы'
//            ),
            array(
                'deny', 'actions' => array('register', 'login', 'forgotPassword', 'loginByHash', 'forgotPasswordInstructions'), 'expression' => '!Yii::app()->user->isGuest', 'message' => 'Вы уже вошли в систему'
            )
        );
    }
}