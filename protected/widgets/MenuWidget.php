<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/3/14
 * Time: 12:57 PM
 * To change this template use File | Settings | File Templates.
 */
class MenuWidget extends CWidget {

	public function init() {}

	public function run(){
		$item = $this->controller->id;
        $coachCategories = $this->controller->getRootCategoriesCoach();
        $programCategories = $this->controller->getRootCategoriesProgram();
        $articleCategories = $this->controller->getRootCategoriesArticle();
        $exerciseCategories = $this->controller->getRootCategoriesExercise();
        $clubDestinations = $this->controller->getRootDestinationsClub();
        $shopCategories = $this->controller->getRootCategoriesShop();

		$this->render('menu',array(
            'item' => $item ? $item : null,
            'coachCategories' => $coachCategories,
            'programCategories' => $programCategories,
            'articleCategories' => $articleCategories,
            'exerciseCategories' => $exerciseCategories,
            'clubDestinations' => $clubDestinations,
            'shopCategories' => $shopCategories,
        ));
	}
}