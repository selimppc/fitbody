<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 18.07.14
 * Time: 13:12
 * Comment: Yep, it's magic
 */

class NewPlaceWidget extends CWidget {

    public function init() {}

    public function run(){
        $isGuest = Yii::app()->controller->isGuest;

        $newClubs = Club::model()->fetchNewPlaces();

        $newShops = Shop::model()->fetchNewPlaces();

        $this->render('newPlace', compact('isGuest', 'newClubs','newShops'));
    }
}