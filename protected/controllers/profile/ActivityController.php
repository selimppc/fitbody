<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 6:54 PM
 */
class ActivityController extends AbstractProfileController {

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'deny', 'actions' => array('add'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы', 'deniedCallback' => array($this, 'redirectToLogin')
            )
        );
    }

    public function actionIndex($user_id) {
        $this->renderLayout($user_id);
        $this->active = 'activity';

        $this->render('profile/activity/list');
    }


}