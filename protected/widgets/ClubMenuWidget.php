<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/20/14
 * Time: 12:55 PM
 */
class ClubMenuWidget extends CWidget {
    public $active = false;

    public function init() {}

    public function run(){

        $this->render('clubMenu',array(
            'category' => ClubDestination::getDestinations(),
            'active'   => $this->active,
        ));
    }
}