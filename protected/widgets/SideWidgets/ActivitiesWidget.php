<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 29.07.14
 * Time: 17:03
 * Comment: Yep, it's magic
 */

class ActivitiesWidget extends CWidget {

    public function init() {}

    public function run() {
        $activities = UserActivity::model()->fetchLastActivity();
        $timekeys = array();
        foreach ($activities as $activity) {
            if ($activity['type'] === AddImageActivity::TYPE_OF_ACTIVITY) {
                $timekeys[$activity['user_id']] = $activity['timekey'];
            }
        }
        if ($timekeys) {
            $imagesCounts = UserActivity::model()->fetchCountImages($timekeys);
            if(!empty($activities) && $imagesCounts){
                foreach($activities as $key => $act){
                    if($act['type'] === AddImageActivity::TYPE_OF_ACTIVITY){
                        foreach($imagesCounts as $elem){
                            if($act['user_id'] == $elem->user_id && $act['timekey'] == $elem->timekey){
                                $activities[$key]['count'] = $elem->count;
                            }
                        }
                    }

                }
            }
        }

//        Debug::print_die($activities);

        $this->render('activities', compact('activities'));
    }
}