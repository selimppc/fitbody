<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 21.07.14
 * Time: 11:31
 * Comment: Yep, it's magic
 */


class SearchController extends FrontController {

    public function actionIndex($class = null) {
        $query = Yii::app()->getRequest()->getParam('q', null);
        $list = Search::getClassList();
        $dataProvider = Search::model()->fetchSearchItems($query, $class);
        $this->render('search', compact('query', 'dataProvider', 'list', 'class'));
    }

//    private $_indexFiles = 'runtime.search';
//
//
//    public function init() {
//        Yii::import('application.vendors.*');
//        require_once('Zend/Search/Lucene.php');
////        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
////        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
////            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ()
////        );
//        parent::init();
//    }
//
//    public function actionCreate() {
//        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
//        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
//            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ());
//        $search = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles), true);
//
//        $apartments = Coach::model()->findAll();
//        //CVarDumper::dump($apartments, 20, true); exit();
//
//        foreach($apartments as $item){
//            $searchDocument = new Zend_Search_Lucene_Document();
//            $searchDocument->addField(Zend_Search_Lucene_Field::Text('name', CHtml::encode($item->name), 'utf-8'));
//            $searchDocument->addField(Zend_Search_Lucene_Field::Text('url', Yii::app()->createUrl('/apartments/main/view', array('id' => $item->id, 'title' => $item->slug)), 'utf-8'));
//            $searchDocument->addField(Zend_Search_Lucene_Field::Text('short_description', CHtml::encode($item->short_description), 'utf-8'));
//            $search->addDocument($searchDocument);
//        }
//        $search->commit();
//        echo 'Файлы успешно созданы';
//    }
//
//
//    public function actionIndex() {
//        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
//        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
//            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ());
//        if (($term = Yii::app()->getRequest()->getParam('q', null)) !== null) {
//            $index = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles));
//            $results = $index->find($term);
//            $query = Zend_Search_Lucene_Search_QueryParser::parse($term);
//
//            foreach($results as $result):
//                CVarDumper::dump($result->name, 20, true); exit();
//            endforeach;
//            CVarDumper::dump($results, 20, true); exit();
//            $this->render('search', compact('results', 'term', 'query'));
//        }
//    }

}