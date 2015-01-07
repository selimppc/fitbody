<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/10/14
 * Time: 12:16 PM
 */
class NewsController extends FrontController{

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

    public function actionIndex(){
        $articles = Article::getNews();

        $this->render('news', array(
            'title'             => 'Статьи',
            'articles'          => $articles->data,
            'widgetCategory'    => false,
            'widgetSubcategory' => false,
            'pages'             => $articles->getPagination()
        ));
    }

    public function actionCategory($slug){
        if(!$slug || !$category = ArticleCategory::getBySlug($slug)){
            throw new CHttpException(404,'Данной категории не существует.');
        }

        $articles = Article::getNews($category->id);

        $this->render('news', array(
            'title'             => $category->category,
            'articles'          => $articles->data,
            'widgetCategory'    => $category->id,
            'widgetSubcategory' => false,
            'pages'             => $articles->getPagination(),
        ));
    }

    public function actionSubCategory($slug){
        if(!$slug || !$subcategory = ArticleSubcategory::getBySlug($slug)){
            throw new CHttpException(404,'Данной подкатегории не существует.');
        }

        $articles = Article::getNews(null, $subcategory->id);

        $this->render('news', array(
            'title'             => $subcategory->title,
            'articles'          => $articles->data,
            'widgetCategory'    => $subcategory->category->id,
            'widgetSubcategory' => $subcategory->id,
            'pages'             => $articles->getPagination(),
        ));
    }
}