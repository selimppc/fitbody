<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 22.07.14
 * Time: 10:21
 * Comment: Yep, it's magic
 */

class BannerWidget extends CWidget {

    public $positionId;

    public function init() {}

    public function run() {
        $banner = Banner::model()->fetchBannerByPosition($this->positionId);
        $this->render('banner', compact('banner'));
    }
}