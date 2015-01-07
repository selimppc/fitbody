<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 1:24 PM
 */
class GoalsController extends AbstractProfileController {

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'deny', 'actions' => array('add, edit, getList'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы', 'deniedCallback' => array($this, 'redirectToLogin')
            )
        );
    }

    public function actionIndex($user_id) {
        $this->renderLayout($user_id);
        $this->active = 'goals';
        Yii::app()->clientScript
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerScriptFile('/js/front/popup.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/popup_init.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/goals.js', CClientScript::POS_HEAD);

        $goals = ProfileGoalLink::model()->findAllByAttributes(array('profile_id' => $this->profile->id));
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
        foreach($goals as $key => $val){
            foreach($goal_groups[$val->type] as $k => $v){
                if($val->goal_id == $v->id)
                    $goals[$key]->goal = $v;
            }
        };

        $goal_fat = new GoalFat();
        $goal_size = new GoalSize();
        $goal_weight = new GoalWeight();
        $goal_progress = new GoalProgress();
        $bodyPartsList = CHtml::listData(BodyPart::getActive(), 'id', 'title');

        $this->render('profile/goals/list', compact('goals','goal_fat','goal_size','goal_weight', 'bodyPartsList', 'goal_progress'));
    }

    public function actionAdd() {
        $user_id = Yii::app()->user->id;
        if(!$user_id){
            throw new CHttpException(404,'You are not authorized.');
        }
        $this->active = 'goals';
        $activeTab = 'size';
        $url = Yii::app()->createUrl('profile/'.$user_id.'/goals');

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/profile/goals.add.js', CClientScript::POS_END)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css');

        $this->renderLayout($user_id);

        $modelGoalFat = new GoalFat();
        if ($post = Yii::app()->request->getPost('GoalFat')) {
            $modelGoalFat->attributes = $post;
            $modelGoalFat->profile_id = $this->profile->id;
            if ($modelGoalFat->save()) {
                $this->redirect($url);
            } else {
                $activeTab = 'fat';
            }
        }

        $modelGoalSize = new GoalSize();
        $bodyPartsList = CHtml::listData(BodyPart::getActive(), 'id', 'title');
        if ($post = Yii::app()->request->getPost('GoalSize')) {
            $modelGoalSize->attributes = $post;
            $modelGoalSize->profile_id = $this->profile->id;
            if ($modelGoalSize->save()) {
                $this->redirect($url);
            } else {
                $activeTab = 'size';
            }
        }

        $modelGoalWeight = new GoalWeight();
        if ($post = Yii::app()->request->getPost('GoalWeight')) {
            $modelGoalWeight->attributes = $post;
            $modelGoalWeight->profile_id = $this->profile->id;
            if ($modelGoalWeight->save()) {
                $this->redirect($url);
            } else {
                $activeTab = 'weight';
            }
        }

        $this->render('profile/goals/add', compact('modelGoalFat', 'bodyPartsList', 'modelGoalSize', 'modelGoalWeight', 'activeTab'));
    }

    public function actionEdit(){
        $user_id = Yii::app()->user->id;
        if(!$user_id){
            throw new CHttpException(404,'You are not authorized.');
        }
        $profile = Profile::getByUserId($user_id);
        if(!$profile){
            throw new CHttpException(404,'You are not authorized.');
        }
        if (Yii::app()->request->isAjaxRequest){
            $this->handleForm('weight');
            $this->handleForm('size');
            $this->handleForm('fat');
            echo json_encode(array('success' => false, 'errors' => array('Отсутствует форма')));
        }
        Yii::app()->end();
    }

    private function handleForm($name){
        if($post = Yii::app()->request->getPost('Goal'.ucfirst($name))){
            if(isset($post['id'])){
                $id = $post['id'];
                if($goalLink = ProfileGoalLink::getByTypeGoalId($name,$id)){
                    $model = 'Goal'.ucfirst($name);
                    $goal = $model::model()->findByPk($goalLink->goal_id);
                    $goal->setScenario('edit');

                    $goal->attributes = $post;
                    if($goal->save()){
                        echo json_encode(array('success' => true,'errors' => array(), 'html' => $this->renderPartial('//content/profile/tpl/goal'.ucfirst($name), array('goal' => $model::model()->findByPk($goal->id)), true)));
                    } else {
                        echo json_encode(array('success' => false,'errors' => $goal->getErrors()));
                    }
                } else {
                    echo json_encode(array('success' => false,'errors' => array('Цели с таким id не существует у данного пользователя')));
                }
            } else {
                echo json_encode(array('success' => false,'errors' => array('Отсутствует id модели')));
            }
            Yii::app()->end();
        }
    }

    public function actionGetList(){
        $type = Yii::app()->request->getPost('type');
        if(!$type){
            $this->returnError('Неизвестный тип');
        }
        $goal_id = Yii::app()->request->getPost('goal_id');
        if(!$goal_id){
            $this->returnError('Не указн параметр id цели');
        }
        $user_id = Yii::app()->user->id;
        if(!$user_id){
            $this->returnError('Неизвестный пользователь');
        }
        $profile = Profile::getByUserId($user_id);
        if(!$profile){
            $this->returnError('Неизвестный профиль');
        }
        $goal_link = ProfileGoalLink::getByTypeGoalId($type, $goal_id);
        if(!$goal_link || ($goal_link->profile_id != $profile->id)){
            $this->returnError('Цель не принадлежит данному пользователю');
        }
        $model = 'Goal'.ucfirst($type);
        $goal = $model::model()->findByPk($goal_id);
        if(!$goal){
            $this->returnError('Цель не найдена');
        }
        if($type == 'fat'){
            echo json_encode(array('success' => true,'data' => array(
                'fat'    => GoalProgress::getList(GoalProgress::TYPE_FAT, $goal_id, $goal->start_fat, $goal->end_fat),
                'weight' => GoalProgress::getList(GoalProgress::TYPE_WEIGHT, $goal_id, $goal->start_weight, $goal->end_weight)
            )));
        } elseif($type == 'size'){
            echo json_encode(array('success' => true,'data' => GoalProgress::getList(GoalProgress::TYPE_SIZE, $goal_id, $goal->start_size, $goal->end_size)));
        } elseif($type == 'weight'){
            echo json_encode(array('success' => true,'data' => GoalProgress::getList(GoalProgress::TYPE_HEFT, $goal_id, $goal->weight_start, $goal->weight_end)));
        } else {
            $this->returnError('Неизвестный тип');
        }
        Yii::app()->end();
    }

    protected function returnError($text){
        echo json_encode(array('success' => false,'errors' => array($text)));
        Yii::app()->end();
    }

    public function actionGetBlock(){

    }

    public function actionRemove(){
        if(!$user_id = Yii::app()->user->id){
            $this->returnError('Неизвестный пользователь');
        }
        if(!$profile = Profile::getByUserId($user_id)){
            $this->returnError('Неизвестный профиль');
        }
        $id = Yii::app()->request->getPost('goal_id');
        if($id){
            $type_string = Yii::app()->request->getPost('type');
            $goal_link = ProfileGoalLink::getByTypeGoalId($type_string, $id);
            if(!$goal_link || ($goal_link->profile_id != $profile->id)){
                $this->returnError('Цель не принадлежит данному пользователю');
            } else {
                $model = 'Goal'.ucfirst($type_string);
                if(!$goal = $model::model()->findByPk($id)){
                    $this->returnError('Цель не найдена');
                } else {
                    echo json_encode(array('success' => ($goal->delete() && $goal_link->delete())));
                    Yii::app()->end();
                }
            }
        } else {
            $this->returnError('Отсутствуют данные');
        }
        $this->returnError('Цель не найдена');
    }

    public function actionRemoveProgress(){
        if(!Yii::app()->user->id){
            $this->returnError('Неизвестный пользователь');
        }
        $id = Yii::app()->request->getPost('id');
        if($id){
            if(!$progress = GoalProgress::model()->findByPk($id)){
                $this->returnError('Данного прогресса не существует');
            }
            $type_string = $progress->type == GoalProgress::TYPE_HEFT ? 'weight' : ($progress->type == GoalProgress::TYPE_SIZE ? 'size' : 'fat');
            if($progress){
                $type = $progress->type;
                $goal_id = $progress->goal_id;
                $progress->delete();
                $last = GoalProgress::getLast($type, $goal_id);
                if($type == GoalProgress::TYPE_WEIGHT){
                    $goal = GoalFat::model()->findByPk($goal_id);
                    if(!$goal) $this->returnError('Данной цели не существует');
                    $last ? ($goal->current_weight = $last->value) : ($goal->current_weight = $goal->start_weight);
                } elseif($type == GoalProgress::TYPE_FAT){
                    $goal = GoalFat::model()->findByPk($goal_id);
                    if(!$goal) $this->returnError('Данной цели не существует');
                    $last ? ($goal->current_fat = $last->value) : ($goal->current_fat = $goal->start_fat);
                } elseif($type == GoalProgress::TYPE_SIZE){
                    $goal = GoalSize::model()->findByPk($goal_id);
                    if(!$goal) $this->returnError('Данной цели не существует');
                    $last ? ($goal->current_size = $last->value) : ($goal->current_size = $goal->start_size);
                } else{
                    $goal = GoalWeight::model()->findByPk($goal_id);
                    if(!$goal) $this->returnError('Данной цели не существует');
                    $last ? ($goal->weight_current = $last->value) : ($goal->weight_current = $goal->weight_start);
                }
                $model = 'Goal'.ucfirst($type_string);
                echo json_encode(array('success' => $goal->save(), 'block' => $this->renderPartial('//content/profile/tpl/goal'.ucfirst($type_string), array('goal' => $model::model()->findByPk($goal_id)), true)));
                Yii::app()->end();
            } else {
                $this->returnError('Данной записи не существует');
            }
        } else {
            $this->returnError('Отсутствуют данные');
        }
    }

    public function actionProgressAdd(){
        if(!$post = Yii::app()->request->getPost('GoalProgress')){
            $this->returnError('Форма с данными отсутствует');
        }
        if(!$user_id = Yii::app()->user->id){
            $this->returnError('Неизвестный пользователь');
        }
        if(!$profile = Profile::getByUserId($user_id)){
            $this->returnError('Неизвестный профиль');
        }
        $type_string = $post['type'] == GoalProgress::TYPE_HEFT ? 'weight' : ($post['type'] == GoalProgress::TYPE_SIZE ? 'size' : 'fat');
        $goal_link = ProfileGoalLink::getByTypeGoalId($type_string, $post['goal_id']);
        if(!$goal_link || ($goal_link->profile_id != $profile->id)){
            $this->returnError('Цель не принадлежит данному пользователю');
        }
        $model = 'Goal'.ucfirst($type_string);
        if(!$goal = $model::model()->findByPk($post['goal_id'])){
            $this->returnError('Цель не найдена');
        }
        $date = DateTime::createFromFormat('d.m.Y', $post['date']);
        if($type_string == 'fat'){
            if($post['type'] == GoalProgress::TYPE_WEIGHT){
                $current = 'current_weight';
                $end_date = $goal->end_weight_date;
                $start_date = $goal->start_weight_date;
            } else {
                $current = 'current_fat';
                $end_date = $goal->end_fat_date;
                $start_date = $goal->start_fat_date;
            }
        } else {
            ($post['type'] == GoalProgress::TYPE_SIZE) ? $current = 'current_size' : $current = 'weight_current';
            $end_date = $goal->end_date;
            $start_date = $goal->start_date;
        }
        if($date->format('U') > strtotime($end_date)){
            $this->returnError('Дата не может быть больше даты окончания выполнения цели');
        }
        if($date->format('U') < strtotime($start_date)){
            $this->returnError('Дата не может быть меньше даты начала выполнения цели');
        }
        $progress = new GoalProgress();
        $progress->attributes = $post;
        if($progress->save()){
            $lastProgress = GoalProgress::getLast($post['type'], $post['goal_id']);
            $goal->$current = $lastProgress->value;
            if($goal->save()){
                echo json_encode(array('success' => true,'block' => $this->renderPartial('//content/profile/tpl/goal'.ucfirst($type_string), array('goal' => $goal), true)));
                Yii::app()->end();
            }
        } else {
            echo json_encode(array('success' => false,'errors' => array($progress->getErrors())));
            Yii::app()->end();
        }
        echo json_encode(array('success' => false,'data' => ''));
        Yii::app()->end();
    }
}