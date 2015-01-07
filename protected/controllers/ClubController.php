<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/20/14
 * Time: 12:31 PM
 */
class ClubController extends FrontController{

    public $slug = '';
    public $active = false;
    public $club;

    public $days = array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');
    public $months = array(
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


    public function actionList($category = false){
        ($category && $category != '') ? ($category_id = ClubDestination::getDestinationId($category)) : ($category_id = false);

        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/front/formstyler/jquery.formstyler.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/formstyler/styler_init.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/js/front/formstyler/jquery.formstyler.css');
        $cs->registerScriptFile('/js/front/jquery.jscrollpane.min.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/css/jquery.jscrollpane.css');
        $cs->registerScriptFile('/js/front/jquery.mousewheel.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
        $cs->registerScript('variable', 'window.destination_id ='.($category_id ? $category_id : 0),CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/club/clubList.js', CClientScript::POS_HEAD);

        $pickedClubs = Club::getPickedClubs($category_id);
        $clubs = Club::model()->getClubs($category_id, true);

        $lastNews = ClubNews::getLastNews();

        $this->render('clubList', array(
            'days'     => $this->days,
            'category' => $category,
            'clubs'    => $clubs->data,
            'pages'    => $clubs->getPagination(),
            'pickedClubs'    => $pickedClubs,
            'lastNews' => $lastNews
        ));
    }

    public function actionAbout($slug){
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/front/owl/clubs_inner_init.js', CClientScript::POS_HEAD);
        $this->includeRating($cs);
        $cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/club/clubAbout.js', CClientScript::POS_HEAD);

        $club = Club::getClubBySlug($slug);
        if(!$club){
            throw new CHttpException(404,'The specified club cannot be found.');
        }

        $this->render('club/clubAbout', array(
            'club'    => $club,
            'address' => $club->addressesRel[0],
            'days'    => $this->days,
        ));
    }

    public function actionPrice($slug){
        $this->layout = '//layouts/club';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $club = Club::getClubBySlug($slug);
        if(!$club){
            throw new CHttpException(404,'The specified club cannot be found.');
        }

        $this->slug = $club->slug;
        $this->active = 'price';
        $this->club = $club;

        $this->render('club/clubPrice', array(
            'club'    => $club,
        ));
    }

    public function actionCoaches($slug){
        $this->layout = '//layouts/club';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $club = Club::getClubBySlug($slug);
        if(!$club){
            throw new CHttpException(404,'The specified club cannot be found.');
        }

        $this->slug = $club->slug;
        $this->active = 'coaches';
        $this->club = $club;

        $coaches = CoachClubLink::getClubCoaches($club->id);

        $this->render('club/clubCoaches', array(
            'coaches' => $coaches,
        ));
    }

    public function actionNews($slug){
        $this->layout = '//layouts/club';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $club = Club::getClubBySlug($slug);
        if(!$club){
            throw new CHttpException(404,'The specified club cannot be found.');
        }
        $news = ClubNews::getClubNews($club->id);

        $this->slug = $club->slug;
        $this->active = 'news';
        $this->club = $club;

        $this->render('club/clubNews', array(
            'news' => $news
        ));
    }

    public function actionArticle($slug, $article_slug){
        $this->layout = '//layouts/club';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $club = Club::getClubBySlug($slug);
        if(!$club){
            throw new CHttpException(404,'The specified club cannot be found.');
        }
        $article = ClubNews::getArticleBySlug($article_slug);
        if(!$article){
            throw new CHttpException(404,'The specified article cannot be found.');
        }

        $this->slug = $club->slug;
        $this->active = 'news';
        $this->club = $club;

        $this->render('club/clubArticle', array(
            'article' => $article
        ));
    }

    public function actionReviews($slug){
        $this->layout = '//layouts/club';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $club = Club::getClubBySlug($slug);
        if(!$club){
            throw new CHttpException(404,'The specified club cannot be found.');
        }

        $this->slug = $club->slug;
        $this->active = 'reviews';
        $this->club = $club;

        $this->render('club/clubReviews', array(
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

    public function includeRating($cs){
        $cs->registerScriptFile('/js/front/rating/jquery.rating-2.0.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/rating/rating_readonly_init.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/js/front/rating/jquery.rating.css');
    }

    public function actionGetNearestClubs(){
        $post = Yii::app()->request->getPost('data');
        isset($post['coords']) ? ($coords = $post['coords']) : ($coords = null);
        isset($post['range']) ? ($range = $post['range']) : ($range = null);
        isset($post['destination_id']) ? ($destination_id = $post['destination_id']) : ($destination_id = null);
        $lat = 0;
        $lon = 0;

        if(isset($coords[0]) && isset($coords[1]) && intval($range)){
            $lat = $coords[0];
            $lon = $coords[1];

            $days = array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');


            $clubs = Club::getClubsInRange($lat, $lon, intval($range), $destination_id);
            $marks = array();
            foreach($clubs as $elem){
                $marks[] = array(
                    'name' => $elem['club'],
                    'slug' => $elem['slug'],
                    'lat'  => $elem->addressesRel[0]['lat'],
                    'lon'  => $elem->addressesRel[0]['lon'],
                );
            }
            echo json_encode(array(
                'marks'=> $marks,
                'html' => $this->renderPartial('//content/ajaxClubs', array(
                            'clubs' => $clubs,
                            'days'     => $days,
                        ), true),
            ));
            Yii::app()->end();
        } else {
            echo json_encode(false);
            Yii::app()->end();
        }
    }

}