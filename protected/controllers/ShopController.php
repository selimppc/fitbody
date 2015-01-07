<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/26/14
 * Time: 11:27 AM
 */
class ShopController extends FrontController{

    public $slug = '';
    public $active = false;
    public $shop;

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
        $category_id = false;
        if($category && $category != '') $category_id = ShopCategory::getCategoryId($category);

        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/front/jquery.jscrollpane.min.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/css/jquery.jscrollpane.css');
        $cs->registerScriptFile('/js/front/jquery.mousewheel.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
        $cs->registerScript('variable', 'window.category_id ='.($category_id ? $category_id : 0),CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/shop/list.js', CClientScript::POS_HEAD);

        $pickedShops = Shop::getPickedShops($category_id);
        $shops = Shop::model()->getShops($category_id);

        $lastNews = ShopNews::model()->getLastNews();

        $this->render('shop/list', array(
            'days'     => $this->days,
            'category' => $category,
            'shops'    => $shops->data,
            'pages'    => $shops->getPagination(),
            'pickedShops'    => $pickedShops,
            'lastNews' => $lastNews,
        ));
    }

    public function actionAbout($slug){
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/front/owl/clubs_inner_init.js', CClientScript::POS_HEAD);
        $this->includeRating($cs);
        $cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/shop/about.js', CClientScript::POS_HEAD);

        $shop = Shop::getShopBySlug($slug);
        if(!$shop){
            throw new CHttpException(404,'The specified club cannot be found.');
        }

        $this->render('shop/about', array(
            'shop'    => $shop,
            'address' => $shop->addressesRel[0],
            'days'    => $this->days,
        ));
    }

    public function actionNews($slug){
        $this->layout = '//layouts/shop';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $shop = Shop::getShopBySlug($slug);
        if(!$shop){
            throw new CHttpException(404,'The specified club cannot be found.');
        }
        $news = ShopNews::getShopNews($shop->id);

        $this->slug = $shop->slug;
        $this->active = 'news';
        $this->shop = $shop;

        $this->render('shop/news', array(
            'news' => $news
        ));
    }

    public function actionArticle($slug, $article_slug){
        $this->layout = '//layouts/shop';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $shop = Shop::getShopBySlug($slug);
        if(!$shop){
            throw new CHttpException(404,'The specified shop cannot be found.');
        }
        $article = ShopNews::getArticleBySlug($article_slug);
        if(!$article){
            throw new CHttpException(404,'The specified article cannot be found.');
        }

        $this->slug = $shop->slug;
        $this->active = 'news';
        $this->shop = $shop;

        $this->render('shop/article', array(
            'article' => $article
        ));
    }

    public function actionReviews($slug){
        $this->layout = '//layouts/shop';

        $cs = Yii::app()->clientScript;
        $this->includeRating($cs);

        $shop = Shop::getShopBySlug($slug);
        if(!$shop){
            throw new CHttpException(404,'The specified shop cannot be found.');
        }

        $this->slug = $shop->slug;
        $this->active = 'reviews';
        $this->shop = $shop;

        $this->render('shop/reviews', array());
    }

    public function includeRating($cs){
        $cs->registerScriptFile('/js/front/rating/jquery.rating-2.0.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/front/rating/rating_readonly_init.js', CClientScript::POS_HEAD);
        $cs->registerCssFile('/js/front/rating/jquery.rating.css');
    }

    public function actionGetNearestShops(){
        $post = Yii::app()->request->getPost('data');
        isset($post['coords']) ? ($coords = $post['coords']) : ($coords = null);
        isset($post['range']) ? ($range = $post['range']) : ($range = null);
        isset($post['category_id']) ? ($category_id = $post['category_id']) : ($category_id = null);
        $lat = 0;
        $lon = 0;

        if(isset($coords[0]) && isset($coords[1]) && intval($range)){
            $lat = $coords[0];
            $lon = $coords[1];

            $shops = Shop::getShopsInRange($lat, $lon, intval($range), $category_id);
            $marks = array();
            foreach($shops as $elem){
                $marks[] = array(
                    'name' => $elem['title'],
                    'slug' => $elem['slug'],
                    'lat'  => $elem->addressesRel[0]['lat'],
                    'lon'  => $elem->addressesRel[0]['lon'],
                );
            }
            echo json_encode(array(
                'marks'=> $marks,
                'html' => $this->renderPartial('//content/ajaxShops', array(
                        'shops' => $shops,
                        'days'     => $this->days,
                    ), true),
            ));
            Yii::app()->end();
        } else {
            echo json_encode(false);
            Yii::app()->end();
        }
    }

}