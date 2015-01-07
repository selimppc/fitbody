<?php
/**
 * PhpBBUserBehavior
 *
 * Automatically add/remove/update forum user
 *
 * <pre>
 * return array(
 *
 *     'modules'=>array(
 *          // ...
 *          'phpbb',
 *     }
 *
 *     'components'=>array(
 *         // ...
 *
 *         'db'=>array(
 *             'connectionString' => '...',
 *         ),

 *         'forumDb'=>array(
 *             'class'=>'CDbConnection',
 *             'connectionString' => '...',
 *             'tablePrefix' => 'phpbb_',
 *             'charset' => 'utf8',
 *         ),
 *
 *         'phpBB'=>array(
 *             'class'=>'phpbb.extensions.phpBB.phpBB',
 *             'path'=>'webroot.forum',
 *         ),
 *
 *         'image'=>array(
 *             'class'=>'ext.image.CImageHandler',
 *         ),
 *
 *         'file'=>array(
 *             'class'=>'ext.file.CFile',
 *         ),
 *     ),
 * );
 * </pre>
 *
 * <pre>
 * class User extends CActiveRecord
 * {
 *     public function behaviors()
 *     {
 *         return array(
 *             'PhpBBUserBehavior'=>array(
 *                 'class'=>'phpbb.components.PhpBBUserBehavior',
 *                 'usernameAttribute'=>'username',
 *                 'newPasswordAttribute'=>'new_password',
 *                 'emailAttribute'=>'email',
 *                 'avatarAttribute'=>'avatar',
 *                 'avatarPath'=>'webroot.upload.images.avatars',
 *                 'forumDbConnection'=>'forumDb', // default
 *                 'syncAttributes'=>array(
 *                     'site'=>'user_website',
 *                     'icq'=>'user_icq',
 *                     'from'=>'user_from',
 *                     'occ'=>'user_occ',
 *                     'interests'=>'user_interests',
 *                 )
 *             ),
 *         );
 *     }
 * }
 * </pre>
 *
 * @author ElisDN <mail@elisdn.ru>
 * @link http://www.elisdn.ru
 * @version 1.1
 */

class PhpBBUserBehavior extends CActiveRecordBehavior
{
    /**
     * @var string User username attribute
     */
    public $usernameAttribute = 'nickname';

    /**
     * @var string User email
     */
    public $emailAttribute = 'email';
    /**
     * @var string CDbConnection component
     */
    public $forumDbConnection = 'forumDb';
    /**
     * @var array attributes
     */
    public $syncAttributes = array();

    protected $_beforeModel;

    public function afterSave($event) {
        $model = $this->getOwner();
        if ($model->isNewRecord) {
            Yii::app()->phpBB->userAdd($model->nickname, $model->forum_hash_password, $model->email, 2);
        } else {
            $this->_updateAttributes();
        }
    }

    public function afterFind($event) {
        $this->_beforeModel = clone($this->getOwner());
    }

    public function afterDelete($event) {
        $model = $this->getOwner();
        if (isset(Yii::app()->phpBB))
            Yii::app()->phpBB->userDelete($model->{$this->usernameAttribute});
    }

    protected function _updateAttributes() {
        $model = $this->getOwner();
        $user = $this->_loadBBUserModel($this->_beforeModel->{$this->usernameAttribute});

        if (!$user) return;

        $attrs = array(
            'user_id' => $user->getPrimaryKey(),
            'username' => $model->{$this->usernameAttribute},
            'user_email' => $model->{$this->emailAttribute},
            'user_password' => $model->forum_hash_password,
        );

        foreach ($this->syncAttributes as $attribute => $forumAttribute) {
            $attrs[$forumAttribute] = $model->{$attribute};
        }

        Yii::app()->phpBB->user_update($attrs);
    }

    protected function _loadBBUserModel($username) {
        return PhpBBUser::model()->findByName($username);
    }

}
