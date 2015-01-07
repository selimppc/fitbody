<?php

class CoachController extends FrontController {

    public $layout='//layouts/coach';
    public $coach;


    public function beforeAction($action) {
        Yii::app()->clientScript
            ->registerScriptFile('/js/front/rating/rating_readonly_init.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/rating/jquery.rating-2.0.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/rating/jquery.rating.css')
            ->registerScriptFile('/js/front/owl/trainer_inner_init.js', CClientScript::POS_HEAD);


        return parent::beforeAction($action);
    }

    protected function loadCoach($coachSlug) {
        if (!($this->coach = Coach::model()->fetchCoach($coachSlug))) {
            //$this->redirect(Yii::app()->createUrl('coaches/index'));
            throw new CHttpException(404, 'Указанная запись не найдена');
        }
    }

    public function actionIndex($coachSlug = null) {

        $this->loadCoach($coachSlug);

        $this->render('coach/about',array('coach' => $this->coach));
    }

    public function actionReviews($coachSlug = null) {

        $this->loadCoach($coachSlug);

        $this->render('coach/reviews',array('coach' => $this->coach));
    }

    public function actionNews($coachSlug = null, $newsSlug = null) {

        $this->loadCoach($coachSlug);

        if ($newsSlug && ($newsOne = CoachNews::model()->fetchOneNews($newsSlug))) {
            $this->render('coach/news_one',array('coach' => $this->coach, 'newsOne' => $newsOne));
            Yii::app()->end();
        }

        $news = CoachNews::model()->fetchAllCoachNews($this->coach->id);

        $this->render('coach/news',array('coach' => $this->coach, 'news' => $news));
    }

    public function actionVideo($coachSlug = null) {
        $this->loadCoach($coachSlug);
        $video = CoachVideo::model()->fetchAllCoachVideo($this->coach->id);
        $this->render('coach/video',array('coach' => $this->coach, 'video' => $video));
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