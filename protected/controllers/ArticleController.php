<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/11/14
 * Time: 3:22 PM
 */
class ArticleController extends FrontController{

    public function actionIndex($slug) {
        if(!$slug || !$article = Article::getArticle($slug)){
            throw new CHttpException(404, 'Данной статьи не существует.');
        }

        Yii::app()->clientScript->registerScriptFile('/js/front/comments.js', CClientScript::POS_HEAD);

        //update article view count if user with such session hasn't viewed it
        if(!isset(Yii::app()->session['article']) || !is_array(Yii::app()->session['article'])){
            Yii::app()->session['article'] = array(intval($article->id));
            $article->saveCounters(array('count' => 1));
        } elseif(array_search(intval($article->id), Yii::app()->session['article']) === false){
            $array = Yii::app()->session['article'];
            $array[] = intval($article->id);
            Yii::app()->session['article'] = $array;
            $article->saveCounters(array('count' => 1));
        }

        $other = Article::getOther($article->subcategory->category->id, $article->id);

        $this->render('article', array(
            'article' => $article,
            'other'   => $other,
        ));
    }

}