<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 29.07.14
 * Time: 10:14
 * Comment: Yep, it's magic
 */


class  ActivityController extends FrontController {

    public function actionIndex() {
        $dataProvider = UserActivity::model()->fetchActivities();

        $ids = $this->fetchIds($dataProvider->getData());
        $images = array();
        if (count($ids['imageIds'])) {
            $images = Image::model()->fetchArrayImagesByIds($ids['imageIds']);
        }

        $goals = array();
        if (count($ids['goalIds'])) {
            $goals = ProfileGoalLink::model()->fetchArrayGoalsByIds($ids['goalIds']);
        }

        $this->render('activity', compact('dataProvider', 'images', 'goals'));
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

}