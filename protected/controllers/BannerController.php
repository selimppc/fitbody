<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 18.07.14
 * Time: 16:35
 * Comment: Yep, it's magic
 */

class BannerController extends Controller {

    public function actionIndex($hash) {
        if ($banner = Banner::model()->fetchBannerByHash($hash)) {
            $clickedArray = Yii::app()->session->get('bannerClick', array());
            if (!in_array($hash, $clickedArray)) {
                $banner->saveCounters(array('click_count' => 1));
                Yii::app()->session->add('bannerClick', array_merge($clickedArray, array($hash)));
            }
            $this->redirect($banner->url);
        }
    }

}