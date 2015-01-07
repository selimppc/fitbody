<?php
/**
 * Created by PhpStorm.
 * User: selim
 * Date: 12/29/2014
 * Time: 7:59 PM
 */

class ApiController extends Controller{

    // {{{ *** Members ***
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers
     */
    Const APPLICATION_ID = 'ASCCPE';

    private $format = 'json';
    // }}}
    // {{{ filters
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array();
    } // }}}
    // {{{ *** Actions ***
    // {{{ actionIndex
    public function actionIndex()
    {
        echo CJSON::encode(array(1, 2, 3));
    } // }}}
    // {{{ actionList


    // Actions
    public function actionList()
    {
    }
    public function actionView()
    {
    }
    public function actionCreate()
    {
    }
    public function actionUpdate()
    {
    }
    public function actionDelete()
    {
    }

    public function actionLogout()
    {
        $arr = array('controller'=>$this->id, 'action'=>$this->action->id,'status' =>'OK');
        Yii::app()->user->logout();
        $this->sendJSONResponse($arr);
    }

    public function actionJapi(){

        if(Yii::app()->user->id){
            $loginData = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
            $this->renderJSON($loginData);
        }else{
            $message = array('message'=>'Please Login !');
            $this->renderJSON($message);
        }

    }

    public function actionClientLogin($email, $password, $type){

        if($email && $password && $type=='normal'){

            $result = $this->actionCheckEmail($email);

            if($result != 1){
                $message = array('status'=>0, 'message'=>'No user found!', 'user id'=>'');
                $this->renderJSON($message);
            }else{
                $data = User::model()->findByAttributes(array('email' => $email));
                $user_id = $data->id;
                $status = $data->status;

                $post= array('email'=>$email, 'password'=>$password);
                $model = new LoginForm('login');
                $model->attributes = $post;

                if($status == 2){
                    $data = array('status'=>$status, 'message'=>'Account is not Activated!', 'user id'=>$user_id );
                    $this->renderJSON($data);

                }elseif(!$model->validate()){
                    $data = array('status'=>0, 'message'=>'Password Incorrect', 'user id'=>'' );
                    $this->renderJSON($data);

                }elseif($model->validate() && $model->login()){
                    $data = array('status'=>$status, 'message'=>'Login Successful!', 'user id'=>$user_id );
                    $this->renderJSON($data);
                }
            }

        }else{
            $data = array('status'=>'0', 'message'=>'Invalid Request!', 'user id'=>'' );
            $this->renderJSON($data);
        }

    }

    private function actionCheckEmail($email){
        $sql="SELECT 1 FROM user WHERE email='$email'";
        $cmd=Yii::app()->db->createCommand($sql);
        $result= $cmd -> queryScalar();
        return $result;
    }


    public function actionClientRegistration($fname, $lname, $email, $username, $password, $dob, $gender, $country_id, $city, $type, $facebook_id){

        $model = new User();

        if($type == "normal"){

            $result = $this->actionCheckEmailNickname($email, $username);
            if($result == 1){
                $data = User::model()->findByAttributes(array('email' => $email));
                $user_id = $data->id;
                $status = $data->status;

                $data = array('status'=> $status, 'message'=>'Email or Username already exists!', 'user id'=>$user_id );
                $this->renderJSON($data);
            }else{
                $model->first_name = $fname;
                $model->last_name = $lname;
                $model->email = $email;
                $model->nickname = $username;
                $model->password = $password;
                $model->country_id = $country_id;
                $model->city = $city;
                $model->birthday = $dob;
                $model->gender = $gender;
                $model->role_id = User::USER_USER;
                $model->last_login_date = date('Y-m-d H:i:s');
                $model->status = User::STATUS_INACTIVE;
                $model->attachEventHandler('onAfterRegister', array(new RegisterActivity(), 'addActivity'));
                $model->attachEventHandler('onAfterRegister', array('EmailNotificationManager', 'sendEmailRegisterUser'));

                if($model->save()) {
                    $data1 = User::model()->findByAttributes(array('email' => $model->email));
                    $user_id1 = $data1->id;
                    $status1 = $data1->status;

                    $data = array('status'=>$status1, 'message'=>'Saved Successfully!', 'user id'=>$user_id1 );
                    $this->renderJSON($data);
                }else{
                    $data = array('status'=>0, 'message'=>'Invalid Request!', 'user id'=>'' );
                    $this->renderJSON($data);
                }
            }

        }elseif($type == "facebook"){

            $result = $this->actionCheckEmailFb($facebook_id);

            if($result == 1){

                $results = $this->actionCheckEmails($email);
                if($results == 1){
                    $dataFb = User::model()->findByAttributes(array('email' => $email));
                    $user_id = $dataFb->id;
                    $status = $dataFb->status;

                    $data = array('status'=> $status, 'message'=>'Facebook ID already exists!', 'user id'=>$user_id );
                    $this->renderJSON($data);
                }else{
                    $data = array('status'=> 0, 'message'=>'Email does not exist!', 'user id'=>'' );
                    $this->renderJSON($data);
                }

            }else{
                $model->first_name = $fname;
                $model->last_name = $lname;
                $model->email = $email;
                $model->nickname = $email;
                $model->password = $password;

                $model->facebook_id = $facebook_id;

                $model->role_id = User::USER_USER;
                $model->last_login_date = date('Y-m-d H:i:s');
                $model->status = User::STATUS_INACTIVE;
                $model->attachEventHandler('onAfterRegister', array(new RegisterActivity(), 'addActivity'));
                $model->attachEventHandler('onAfterRegister', array('EmailNotificationManager', 'sendEmailRegisterUser'));

                if( $model->save() ) {
                    $data1 = User::model()->findByAttributes(array('email' => $model->email));
                    $user_id1 = $data1->id;
                    $status1 = $data1->status;

                    $data = array('status'=>$status1, 'message'=>'Saved Successfully!', 'user id'=>$user_id1 );
                    $this->renderJSON($data);
                }else{
                    $data = array('status'=>0, 'message'=>'Invalid Requests!', 'user id'=>'' );
                    $this->renderJSON($data);
                }
            }

        }else{
            $data = array('status'=>'0', 'message'=>'Invalid Request!', 'user id'=>'');
            $this->renderJSON($data);
        }

    }

    private function actionCheckEmailNickname($email, $nickname){
        $sql="SELECT 1 FROM user WHERE email='$email' OR nickname='$nickname' ";
        $cmd=Yii::app()->db->createCommand($sql);
        $result= $cmd -> queryScalar();
        return $result;
    }
    private function actionCheckEmails($email){
        $sql="SELECT 1 FROM user WHERE email='$email' ";
        $cmd=Yii::app()->db->createCommand($sql);
        $result= $cmd -> queryScalar();
        return $result;
    }

    private function actionCheckEmailFb($facebook_id){
        $sql="SELECT 1 FROM user WHERE facebook_id='$facebook_id' ";
        $cmd=Yii::app()->db->createCommand($sql);
        $result= $cmd -> queryScalar();
        return $result;
    }



}