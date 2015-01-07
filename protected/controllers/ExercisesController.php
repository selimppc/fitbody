<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/12/14
 * Time: 11:55 AM
 */
class ExercisesController extends FrontController{

    public function checkType($type){
        if($type === 'with-weights')
            return 1;
        elseif($type === 'trx')
            return 2;
        else
            throw new CHttpException(404,'Такая категория упражнений отсутствует.');
    }

    public function actionIndex($type){
        $type_id = $this->checkType($type);

        $exercises = Exercise::getData($type_id);

        $this->render('exercises', array(
            'exercises' => $exercises->data,
            'pages'     => $exercises->getPagination(),
            'type'      => $type,
        ));
    }

    public function actionCategory($type, $category){
        $type_id = $this->checkType($type);

        if(!$muscle = Muscle::getMuscle($category)){
            throw new CHttpException(404,'The specified exercise category cannot be found.');
        }

        $exercises = Exercise::getData($type_id, $muscle->id);

        $this->render('exercisesCategory', array(
            'muscle'    => $muscle,
            'exercises' => $exercises->data,
            'pages'     => $exercises->getPagination(),
            'type'      => $type,
        ));
    }
}