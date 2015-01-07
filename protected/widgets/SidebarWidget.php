<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/3/14
 * Time: 1:21 PM
 * To change this template use File | Settings | File Templates.
 */
class SidebarWidget extends CWidget {

    public $organizer = true;
    public $coaches = true;
    public $newPlace = true;
    public $upperRightBanner = true;
    public $bottomRightBanner = true;

	public function init() {}

	public function run() {
		$this->render('sidebar',array(
            'coaches'   => $this->coaches,
            'organizer' => $this->organizer,
            'newPlace'  => $this->newPlace,
            'upperRightBanner' => $this->upperRightBanner,
            'bottomRightBanner' => $this->bottomRightBanner,
        ));
	}
}