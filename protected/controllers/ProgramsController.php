<?php

class ProgramsController extends FrontController {

    public function actionIndex($slug = null) {
        
        $categoryTitle = null;
        $categoryId = null;
        if ($slug) {
            $category = ProgramCategory::model()->slugCondition($slug)->statusCondition()->find();
            if ($category) {
                $categoryId = $category->id;
                $categoryTitle = $category->title;
            }
        }
        $popularPrograms = Program::model()->fetchPopularPrograms();
        $programs = Program::model()->getPrograms($categoryId);

        $this->render('programs', compact('programs', 'popularPrograms', 'slug', 'categoryTitle'));
    }

}