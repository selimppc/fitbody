<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 18.07.14
 * Time: 15:34
 * Comment: Yep, it's magic
 */

class AsideBannerWidget extends CWidget {

    public $positionId;

    public function init() {}

    public function run() {
        $banner = Banner::model()->fetchBannerByPosition($this->positionId);
        $this->render('asideBanner', compact('banner'));
    }
}