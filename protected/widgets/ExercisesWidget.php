<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/12/14
 * Time: 1:49 PM
 */
class ExercisesWidget extends CWidget {

    public $active = 0;

    public function init() {
    }

    public function run(){
        $rs = Muscle::model()->findAll();

        $muscles = array();
        foreach($rs as $elem){
            $muscles[$elem->id] = $elem;
        }
        $i = 1;
        $left  = array();
        $right = array();
        for(; $i <= 7; $i++){
            $left[$i] = $muscles[$i];
        }
        for(; $i <= 15; $i++){
            $right[$i] = $muscles[$i];
        }

        $this->render('exercises',array(
            'left'  => $left,
            'right' => $right,
            'active' => $this->active,
        ));
    }
}