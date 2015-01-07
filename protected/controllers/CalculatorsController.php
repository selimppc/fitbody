<?php

class CalculatorsController extends FrontController {

    public function actionIndex($slug = null) {

        //TODO::calculator category off
        $calculators = Calculator::model()->getCalculators();
        $this->render('calculators', compact('calculators'));
    }


}