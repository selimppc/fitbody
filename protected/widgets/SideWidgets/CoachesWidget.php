<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 18.07.14
 * Time: 12:17
 * Comment: Yep, it's magic
 */

class CoachesWidget extends CWidget {

    public function init() {}

    public function run() {
        $coaches = Coach::model()->fetchRecommendedCoaches();
        $this->render('coaches', compact('coaches'));
    }
}