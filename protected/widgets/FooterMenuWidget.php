<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/3/14
 * Time: 1:29 PM
 * To change this template use File | Settings | File Templates.
 */
class FooterMenuWidget extends CWidget {

	public function init() {}

	public function run() {
        $muscleCriteria = new CDbCriteria();
        $muscleCriteria->limit = 15;
        $rs = Muscle::model()->findAll($muscleCriteria);
        $muscles = array();
        foreach($rs as $elem){
            $muscles[$elem->id] = $elem;
        }

        $programCategories = $this->controller->getRootCategoriesProgram();
        $coachCategories = $this->controller->getRootCategoriesCoach();
        $articleCategories = $this->controller->getRootCategoriesArticle();
        $exerciseCategories = $this->controller->getRootCategoriesExercise();

		$this->render('footerMenu',array(
            'articleCategories' => $articleCategories,
            'exerciseCategories'  => $exerciseCategories,
            'programCategories' => $programCategories,
            'coachCategories' => $coachCategories
        ));
	}
}