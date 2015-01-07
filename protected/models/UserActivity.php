<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 29.07.14
 * Time: 10:34
 * Comment: Yep, it's magic
 */

class UserActivity extends CActiveRecord {

    public $timekey;
    public $count;

    const LIMIT_ACTIVITIES_ON_PAGE = 10;
    const LIMIT_ACTIVITIES_ON_ASIDE = 5;


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array();
    }

    public function tableName() {
        return 'user_activity';
    }

    public function relations() {
        return array();
    }

    public function attributeLabels() {
        return array();
    }


    public function fetchCountActivities($user_id = null) {
        $countSql = 'SELECT
                            COUNT(*)
                        FROM
                            (SELECT
                                `user_activity`.`id`,
                                    DATEDIFF(NOW(), `user_activity`.`created_at`) as timekey
                            FROM
                                `user_activity`
                            LEFT JOIN
                                `user` ON `user_activity`.`user_id` = `user`.`id`
                            WHERE `user`.`status` = :userStatus '.($user_id ? 'AND `user_activity`.`user_id` = :user_id' : '').'
                            GROUP BY type , CASE
                                WHEN (type = "AddImage") THEN `user_activity`.`user_id`
                                ELSE `user_activity`.`id`
                            END , timekey) as groupQuery';
        $command = Yii::app()->db->createCommand($countSql);
        $user_id ? $command->bindValue(':user_id', $user_id) : '';
        $command->bindValue(':userStatus', User::STATUS_ACTIVE);
        return $command->queryScalar();
    }
    public function fetchActivities($user_id = null) {
        $count = $this->fetchCountActivities($user_id);
        $sql = 'SELECT
                `user_activity`.`id`,
                `user_activity`.`user_id`,
                `user_activity`.`type`,
                `user_activity`.`source_id`,
                `user_activity`.`created_at`,
                `user`.`email`,
                `user`.`last_name`,
                `user`.`first_name`,
                `user`.`nickname`,
				`image`.`image_filename`,
				`image`.`alt`,
                DATEDIFF(NOW(), `user_activity`.`created_at`) as timekey,
                CASE
                    WHEN
                        (`user_activity`.`type` = "AddImage")
                    THEN
                        GROUP_CONCAT(DISTINCT `user_activity`.`source_id`
                            ORDER BY `user_activity`.`created_at` ASC
                            SEPARATOR ", ")
                    ELSE `user_activity`.`id`
                END ids
            FROM
                `user_activity`
                    LEFT JOIN
                `user` ON `user_activity`.`user_id` = `user`.`id`
			LEFT JOIN `image` ON `user`.`image_id` = `image`.`id`
            WHERE `user`.`status` = :userStatus '.($user_id ? 'AND `user_activity`.`user_id` = :user_id' : '').'
            GROUP BY type , CASE
                WHEN (type = "AddImage") THEN `user_activity`.`user_id`
                ELSE `user_activity`.`id`
            END , timekey
            ORDER BY `user_activity`.`created_at` DESC,
                     `user_activity`.`id` DESC';
        $params = array();
        $params[':userStatus'] = User::STATUS_ACTIVE;
        if(intval($user_id)){
            $params[':user_id'] = $user_id;
        }
        return new CSqlDataProvider($sql, array(
            'params' => $params,
            'totalItemCount'=> $count,
            'pagination'=>array(
                'pageSize' => self::LIMIT_ACTIVITIES_ON_PAGE,
            ),
        ));
    }

    public function fetchLastActivity() {
        $sql = 'SELECT
                    `user_activity`.`id`,
                    `user_activity`.`user_id`,
                    `user_activity`.`type`,
                    `user_activity`.`source_id`,
                    `user_activity`.`created_at`,
                    `user`.`email`,
                    `user`.`last_name`,
                    `user`.`first_name`,
                    `user`.`nickname`,
                    DATEDIFF(NOW(), `user_activity`.`created_at`) as timekey
                FROM
                    `user_activity`
                        LEFT JOIN
                    `user` ON `user_activity`.`user_id` = `user`.`id`
                        JOIN
                    (SELECT
                        `user_id`, max(`created_at`) as `mtime`
                    FROM
                        `user_activity`
                    GROUP BY `user_id`) as t1 ON `user_activity`.`user_id` = t1.`user_id`
                        AND `user_activity`.`created_at` = t1.`mtime`
                WHERE
                    `user`.`status` = :userStatus
                GROUP BY `user_activity`.`user_id`
                ORDER BY `user_activity`.`created_at` DESC LIMIT :limit';

        return Yii::app()->db->createCommand($sql)->bindValue(':userStatus', User::STATUS_ACTIVE)
                                                        ->bindValue(':limit', self::LIMIT_ACTIVITIES_ON_ASIDE)
                                                        ->queryAll();
    }
    
    public function fetchCountImages($timekeys) {
        $criteria = new CDbCriteria();
        $criteria->select = 'COUNT(user_id) as `count`, `t`.`created_at`, `t`.`user_id`, DATEDIFF(NOW(), `t`.`created_at`) as timekey';
        $criteria->params = array();
        $cnt = 0;
        foreach ($timekeys as $userId => $timekey) {
            $criteria->addCondition('user_id = :user_id' . $cnt . ' AND DATEDIFF(NOW(), `t`.`created_at`) = :timekey' . $cnt, 'OR');
            $criteria->params[':user_id' . $cnt] = $userId;
            $criteria->params[':timekey' . $cnt] = $timekey;
            $cnt++;
        }
        $criteria->addCondition('type = :type');
        $criteria->params[':type'] = AddImageActivity::TYPE_OF_ACTIVITY;
        $criteria->group = 'user_id';
        return $this->findAll($criteria);

    }
}