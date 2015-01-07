<?php

class ProgramController extends FrontController {

    public function actionIndex($slug = null) {

        $program = Program::model()->getProgram($slug);

        if (!$program) {
            throw new CHttpException(404, 'Указанная запись не найдена');
        }

        $this->registerScripts();

        $breadcrumbs = ProgramCategory::model()->getCategoryBreadcrumbs($program->category->id);
        $daysOfWeekOrdinal = ProgramDay::model()->getDaysOfWeekOrdinal();
        $daysOfWeek = ProgramDay::model()->getDaysOfWeek();

        $this->render('program', compact('program', 'breadcrumbs', 'daysOfWeekOrdinal', 'daysOfWeek'));
    }

    protected function registerScripts() {
        Yii::app()->clientScript
            ->registerScriptFile('/js/front/jquery.jscrollpane.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/jquery.mousewheel.js', CClientScript::POS_HEAD)
            ->registerCssFile('/css/jquery.jscrollpane.css')

            ->registerScriptFile('/js/front/comments.js', CClientScript::POS_HEAD);
    }


}