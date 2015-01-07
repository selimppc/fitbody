<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/27/14
 * Time: 12:53 PM
 */
class ClubTabsWidget extends CWidget {
    public $active = false;
    public $slug   = '';
    public $club_id;

    public function init() {}

    public function run(){
        $count = ClubReview::countReviews($this->club_id);

        $this->render('clubTabs',array(
            'slug'     => $this->slug,
            'active'   => $this->active,
            'club_id'  => $this->club_id,
            'count'    => $count,
        ));
    }
}