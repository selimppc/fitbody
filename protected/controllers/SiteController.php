<?php

class SiteController extends FrontController{
    public $pageName = 'about';

    public $month = array(
        1 => array('Январь', 'Января'),
        2 => array('Февраль', 'Февраля'),
        3 => array('Март', 'Марта'),
        4 => array('Апрель', 'Апреля'),
        5 => array('Май', 'Мая'),
        6 => array('Июнь', 'Июня'),
        7 => array('Июль', 'Июля'),
        8 => array('Август', 'Августа'),
        9 => array('Сентябрь', 'Сентября'),
        10=> array('Октябрь', 'Октября'),
        11=> array('Ноябрь', 'Ноября'),
        12=> array('Декабрь', 'Декабря')
    );

	public function actionIndex() {

        $indexArticles = Article::getIndexNews(true, false);

        $articles = Article::getIndexNews(false, true);

		$this->render('index', array(
			'indexArticles' => $indexArticles,
            'articles'      => $articles,
		));
	}

    public function actionAbout(){
        $this->pageName = 'about';
        $this->simplePage();
    }

    public function actionAdvertising(){
        $this->pageName = 'advertising';
        $this->simplePage();
    }

    public function actionPartnership(){
        $this->pageName = 'partnership';
        $this->simplePage();
    }

    public function actionContacts(){
        $this->pageName = 'contacts';
        $this->simplePage();
    }

    public function simplePage(){
        $this->render('simple', array(
            'title' => Yii::app()->static->get($this->pageName.'/title'),
            'content' => Yii::app()->static->get($this->pageName.'/content'),
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