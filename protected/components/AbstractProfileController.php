<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 1:03 PM
 */
abstract class AbstractProfileController extends FrontController{

    public $profile;
    public $active;
    public $age = null;
    public $gender;
    public $owner = false;

    public function renderLayout($user_id){
        $this->owner = ($user_id == Yii::app()->user->id);
        $profile = Profile::model()->with(array('user' => array('with' => 'image')))->find('t.user_id = :user_id',array(':user_id' => $user_id));
        if(!$profile){
            throw new CHttpException(404,'There is no such user.');
        } else {
            $this->profile = $profile;
        }
        if(!$this->owner && !$this->profile->show_profile){
            $this->redirect('/profile/'.$user_id.'/hidden.html');
            Yii::app()->end();
        }

        //age
        if($this->profile->user->birthday != '0000-00-00'){
            $this->age = floor((strtotime(date('Y-m-d')) - strtotime($this->profile->user->birthday)) / 31556926);
            switch (true){
                case ($this->age == 1):
                    $this->age .= ' год';
                    break;
                case ($this->age > 1 && $this->age < 5):
                    $this->age .= ' года';
                    break;
                case ($this->age >= 5 && $this->age < 21):
                    $this->age .= ' лет';
                    break;
                case (($this->age % 10) == 1):
                    $this->age .= ' год';
                    break;
                case (($this->age % 10) > 1 && ($this->age % 10) < 5):
                    $this->age .= ' года';
                    break;
                case (($this->age % 10) >= 5):
                    $this->age .= ' лет';
                    break;
                default:
                    break;
            }
        }

        //gender
        $this->gender = '';
        if($this->profile->user->gender == 'male') $this->gender =  'Мужчина';
        if($this->profile->user->gender == 'female') $this->gender =  'Женщина';

        $this->layout = '//layouts/profile';
    }

}