<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 12.07.14
 * Time: 13:13
 * Comment: Yep, it's magic
 */

class CalculatorController extends FrontController {

    public function actionIndex($slug = null) {
        $calculator = $this->loadCalculator($slug);
        $this->registerScripts();
        $this->render('calculator', compact('calculator'));
    }

    protected function loadCalculator($slug) {
        if (!$calculator = Calculator::model()->getCalculator($slug)) {
            throw new CHttpException(404, 'Указанная запись не найдена');
        }
        return $calculator;
    }
    protected function registerScripts() {
        Yii::app()->clientScript
            ->registerCssFile('/css/jquery.jscrollpane.css')
            ->registerScriptFile('/js/front/jquery.jscrollpane.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/jquery.mousewheel.js', CClientScript::POS_HEAD);
    }

}