<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/1/14
 * Time: 4:32 PM
 */
class ProfileController extends AbstractProfileController {

    public function actionIndex($user_id) {
        $this->renderLayout($user_id);
        $this->active = 'index';

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/comments.js', CClientScript::POS_HEAD);

        //progress
        $progress = ProfileProgress::getAll($this->profile->id);
        $now = null;
        $before = null;
        $progress_count = 0;
        foreach($progress as $key => $elem){
            if($elem->before_image)
                $progress_count++;
            if($elem->now_image)
                $progress_count++;
            if($elem->now_main == 1){
                $now = $elem;
            }
            if($elem->before_main == 1){
                $before = $elem;
            }
        }

        //goals
        $goals = ProfileGoalLink::getLast($this->profile->id);
        $goal_groups = array(
            'fat'    => array(),
            'size'   => array(),
            'weight' => array()
        );
        foreach($goals as $key => $val){
            array_push($goal_groups[$val->type], $val->goal_id);
        };
        foreach($goal_groups as $key => $val){
            $model = 'Goal'.ucfirst($key);
            $goal_groups[$key] = $model::getByIds($goal_groups[$key]);
        }
        if(count($goal_groups['fat']) > 2){
            $goals = array_slice($goal_groups['fat'], 0, 2);
        } else {
            $temp_arr = array();
            $fat = false;
            $count = 0;
            foreach($goals as $outer){
                foreach($goal_groups[$outer->type] as $inner){
                    if($outer->goal_id == $inner->id){
                        if($outer->type == 'fat'){
                            if(!$fat){
                                array_push($temp_arr, $inner);
                                $fat = true;
                            }
                        } else {
                            if($count < 2){
                                array_push($temp_arr, $inner);
                                $count++;
                            }
                        }
                    }
                }
            }
            $goals = $temp_arr;
        }

        //$photo
        $images = ProfilePhoto::getPhoto($this->profile->id, 5);

        //activities
        $dataProvider = UserActivity::model()->fetchActivities($user_id);

        $ids = $this->fetchIds($dataProvider->getData());
        $activity_images = array();
        if (count($ids['imageIds'])) {
            $activity_images = Image::model()->fetchArrayImagesByIds($ids['imageIds']);
        }

        $activity_goals = array();
        if (count($ids['goalIds'])) {
            $activity_goals = ProfileGoalLink::model()->fetchArrayGoalsByIds($ids['goalIds']);
        }

        $this->render('profile/index', compact('now','before','progress_count','user_id', 'goals', 'images', 'dataProvider', 'activity_images', 'activity_goals'));
    }

    protected function fetchIds($data) {
        $imageIds = array();
        $goalIds = array();
        foreach ($data as $item) {
            if ($item['type'] === AddImageActivity::TYPE_OF_ACTIVITY) {
                $imageIds = array_merge($imageIds, explode(',', $item['ids']));
            }
            if ($item['type'] === AddGoalActivity::TYPE_OF_ACTIVITY) {
                array_push($goalIds, $item['source_id']);
            }
        }
        return array('imageIds' => $imageIds, 'goalIds' => $goalIds);
    }

    public function actionHide($user_id){
        $this->render('profile/hidden');
    }
}