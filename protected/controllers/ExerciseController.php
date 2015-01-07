<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/12/14
 * Time: 4:23 PM
 */
class ExerciseController extends FrontController{

    public function actionIndex($itemId){
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/front/formstyler/jquery.formstyler.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/formstyler/styler_init.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/js/front/formstyler/jquery.formstyler.css');

        $cs->registerScriptFile('/js/front/jquery.jscrollpane.min.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/css/jquery.jscrollpane.css');
        $cs->registerScriptFile('/js/front/jquery.mousewheel.js', CClientScript::POS_HEAD);


        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.id = :id";
        $criteria->params = array(':id' => $itemId, ':status' => 1);
        $exercise = Exercise::model()->with(array('muscle','muscles','images','instructions'))->find($criteria);

        if(!$exercise)
            throw new CHttpException(404,'The specified exercise cannot be found.');

        $otherCriteria = new CDbCriteria();
        $otherCriteria->condition = 't.status = :status AND t.muscle_id = :muscle_id AND t.id != :id';
        $otherCriteria->params = array(':status' => 1,':muscle_id' => $exercise->muscle_id, ':id' => $exercise->id);
        $otherCriteria->limit = 4;
        $otherCriteria->order = "t.rating DESC";

        $other = Exercise::model()->with('images')->findAll($otherCriteria);

        $type = $exercise->type == 1 ? 'with-weights' : 'trx';
        $this->render('exercise', array(
            'exercise' => $exercise,
            'other'    => $other,
            'type'     => $type,
        ));
    }

    public function actionError() {
        if($error=Yii::app()->errorHandler->error) {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}