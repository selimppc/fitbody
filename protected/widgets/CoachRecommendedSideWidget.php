<?php

class CoachRecommendedSideWidget extends CWidget {


    public function init() {}

    public function run() {
        $coaches = Coach::model()->fetchRecommendedCoaches();

        $this->render('coachRecommendedSide', compact('coaches'));
    }
}