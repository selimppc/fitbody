<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 12.07.14
 * Time: 15:33
 * Comment: Yep, it's magic
 */

class BooksController extends FrontController {

    public function actionIndex($slug = null) {
        $categoryId = null;
        $categoryTitle = null;
        if ($slug) {
            $category = BookCategory::model()->slugCondition($slug)->statusCondition()->find();
            if ($category) {
                $categoryId = $category->id;
                $categoryTitle = $category->title;
            } else {
                $this->redirect(Yii::app()->createUrl('books/index'));
            }
        }

        $categories = BookCategory::model()->fetchCategories();
        $books = Book::model()->fetchBooks($categoryId);

        $this->render('books', compact('books', 'categories', 'slug', 'categoryTitle'));
    }

    public function actionDownload($hash) {
        if (!$book = Book::model()->fetchDownloadBook($hash)) {
            $this->redirect('/');
        }
        $this->disableProfilers();
        Yii::app()->request->sendFile($book->title . '.' . pathinfo($book->file, PATHINFO_EXTENSION), file_get_contents(DOCUMENT_ROOT . 'public_html/pub/books/' . $book->file));
    }


    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'deny', 'actions' => array('download'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы уже вошли в систему'
            )
        );
    }

}