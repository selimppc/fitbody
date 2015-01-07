<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 */
class User extends CActiveRecord {

	const STATUS_ACTIVE     = 1;
	const STATUS_INACTIVE   = 2;
	const STATUS_SUSPENDED  = 3;
	const STATUS_DELETED    = 4;

	const USER_DEVELOPER    = 1024;
	const USER_GLOBAL_ADMIN = 512;
	const USER_USER         = 10;
	const USER_GUEST        = 0;

	const GENDER_MALE = 'male';
	const GENDER_FEMALE = 'female';

	const AGREE_TERMS = 1;

    public $agreeTerms;
    public $password_confirm;
    private $_urlProfile;
    private $_pathMainImage;

	/**
	 * @return array of user status. Key is a status, value title
	 */
	public function getStatusList() {
		return array(
			self::STATUS_ACTIVE     => Yii::t('common', 'Active'),
			self::STATUS_INACTIVE   => Yii::t('common', 'Inactive'),
			self::STATUS_SUSPENDED  => Yii::t('common', 'Suspended'),
			self::STATUS_DELETED    => Yii::t('common', 'Deleted'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 *
	 * @return User the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'user';
	}

	public function save($runValidation=true,$attributes=null) {
		if(Yii::app()->image->checkUpload('avatar')) {
			if($this->image_id) {
				Yii::app()->image->delete($this->image_id);
			}
			$this->image_id = Yii::app()->image->save('avatar','avatar');
		}

		return parent::save($runValidation, $attributes);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

            //insert/update
			array('nickname, email, role_id', 'required', 'on' => 'insert, update'),
			array('password','required','on'=>'insert'),
			array('email, nickname, last_name, first_name, city', 'length', 'max' => 255, 'on' => 'insert, update'),
            array('email, nickname', 'unique', 'on' => 'insert, update'),
			array('status','in','range'=>array(self::STATUS_ACTIVE,self::STATUS_INACTIVE,self::STATUS_SUSPENDED,self::STATUS_DELETED)/*, 'on' => 'insert, update'*/),
			array('email','email', 'allowEmpty' => false, 'on' => 'insert, update'),
            array('country_id', 'numerical', 'on' => 'insert, update'),
            array('country_id', 'exist', 'className' => 'Country', 'attributeName' => 'id', 'on' => 'insert, update'),
            array('first_name, last_name, email, nickname, password, gender, country_id, city, login, image_id, birthday', 'safe', 'on' => 'insert, update'),
            array('gender', 'in', 'range' => array(self::GENDER_MALE, self::GENDER_FEMALE), 'on' => 'insert, update'),

            //ClientRegistration
            array('first_name, last_name, email, password', 'required', 'on' => 'clientRegistration'),
            array('first_name, last_name, facebook_id, email, nickname, password, gender, country_id, city, login, image_id, birthday', 'safe', 'on' => 'clientRegistration'),

            //register
            array('password, password_confirm, nickname, agreeTerms','required', 'on' => 'register'),
            array('password, password_confirm', 'length', 'min' => 5, 'on' => 'register'),
            array('email, forum_hash_password, nickname, last_name, first_name, city', 'length', 'max' => 255, 'on' => 'register'),
            array('password', 'compare', 'compareAttribute' => 'password_confirm', 'on' => 'register'),
            array('password_confirm', 'compare', 'compareAttribute' => 'password', 'on' => 'register'),
            array('email', 'email', 'allowEmpty' => false, 'on' => 'register'),
            array('email, nickname', 'unique', 'on' => 'register'),
            array('country_id', 'numerical', 'on' => 'register'),
            array('country_id', 'exist', 'className' => 'Country', 'attributeName' => 'id', 'on' => 'register'),
            array('email, password, password_confirm', 'filter', 'filter'=>'trim', 'on' => 'register'),
            array('first_name, last_name, gender, country_id, city', 'safe', 'on' => 'register'),
            array('birthday', 'date', 'format'=>'d.M.yyyy', 'on' => 'register'),
            array('gender', 'in', 'range'=> array(self::GENDER_MALE, self::GENDER_FEMALE), 'on' => 'register'),
            array('agreeTerms', 'in', 'range'=>array(self::AGREE_TERMS), 'on' => 'register', 'message' => 'Требуется согласие с правилами пользования сайтом'),


            //facebook
            array('email', 'email', 'allowEmpty' => false, 'on' => 'facebook'),
            array('facebook_id', 'required', 'on' => 'facebook'),
            array('first_name, last_name, gender, nickname', 'safe', 'on' => 'facebook'),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE), 'on' => 'facebook'),

            //vk
            array('email', 'email', 'allowEmpty' => false, 'on' => 'vk'),
            array('vk_id', 'required', 'on' => 'vk'),
            array('first_name, last_name, gender, nickname', 'safe', 'on' => 'vk'),
            array('status', 'in', 'range' => array(self::STATUS_ACTIVE), 'on' => 'vk'),

            //forgot password
            array('email', 'email', 'allowEmpty' => false, 'on' => 'forgotPassword'),
            array('email', 'exist', 'on' => 'forgotPassword', 'criteria' => array('condition' => 'status = :status', 'params' => array(':status' => User::STATUS_ACTIVE)))

		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user_role'  => array(self::HAS_ONE, 'UserRole', array('id'=>'role_id')),
            'profile'  => array(self::HAS_ONE, 'Profile', array('user_id'=>'id')),
            'country'  => array(self::HAS_ONE, 'Country', array('id'=>'country_id')),
            'image'  => array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
            'id' => 'ID',
			'email' => 'Email',
			'login' => 'Login',
			'birthday' => 'День рождения',
			'gender' => 'Пол',
			'nickname' => 'Никнейм',
			'country_id' => 'Страна',
			'city' => 'Город',
			'first_name' => 'Имя',
			'last_name' => 'Фамилия',
			'password'  => 'Пароль',
			'status'  => 'Статус',
			'role_id'   => 'Роль',
			'agreeTerms'   => 'Правила пользования',
			'password_confirm'   => 'Подтвердить пароль',
		);
	}

    public function beforeSave() {
        if(isset($_POST['User']['password']) && $_POST['User']['password'] != '') {
            $this->password = CPasswordHelper::hashPassword($_POST['User']['password']);
        }
        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
            $this->forum_hash_password = User::generateHash($this->email);
        }

        if ($this->getScenario() === 'register') {
            $this->hash = static::generateHash($this->email);
            $this->hash_created_at = User::generateTimeExpiration();
            if($this->birthday) {
                $date = DateTime::createFromFormat('d.m.Y', $this->birthday);
                $this->birthday = $date->format('Y-m-d');
            }
        }

        if ($this->getScenario() === 'update') {
            if($this->birthday) {
                $date = DateTime::createFromFormat('d.m.Y', $this->birthday);
                if($date)
                    $this->birthday = $date->format('Y-m-d');
            }
        }


        $this->updated_at = new CDbExpression('NOW()');
        return parent::beforeSave();
    }

    public static function generateHash($email) {
        return hash('sha256', $email . time() . rand(1,999));
    }

    public static function generateTimeExpiration() {
        return date('Y-m-d H:i:s', strtotime("+1 day"));
    }

    public function afterSave(){
        if($this->isNewRecord){
            $profile = new Profile();
            $profile->user_id = $this->id;
            $profile->save();
            // add phpBB user
            //Yii::app()->phpBB->userAdd($this->nickname, $this->forum_hash_password, $this->email, 2);
        }

        if($this->hasEventHandler('onAfterRegister')) {
            $event = new CModelEvent($this);
            $this->onAfterRegister($event);
        }

        return parent::afterSave();
    }

    public function onAfterRegister($event) {
        $this->raiseEvent('onAfterRegister', $event);
    }



    public function getGenderList() {
        return array(
          self::GENDER_MALE => 'Мужчина',
          self::GENDER_FEMALE => 'Женщина'
        );
    }

    public static function updateStatus($id){

    }

    public function getUrlProfile() {
        if ($this->_urlProfile === null) {
            $this->_urlProfile = Yii::app()->createUrl('profile/' . $this->id);
        }
        return $this->_urlProfile;
    }

    public static function getUrlProfileById($id) {
        return Yii::app()->createUrl('profile/' . $id);
    }

    public function getPathMainImage() {
        if ($this->_pathMainImage === null) {
            if ($this->image_id) {
                $this->_pathMainImage = '/pub/user_photo/35x35/' . $this->image->image_filename;
            } else {
                $this->_pathMainImage = '/images/blank/35x35_comment_avatar.png';
            }
        }
        return $this->_pathMainImage;
    }

    public static function getPathMainImageByFilename($filename) {
        if ($filename) {
            return  '/pub/user_photo/35x35/' . $filename;
        }

        return '/images/blank/35x35_comment_avatar.png';
    }

    public function behaviors() {
        return array(
            'PhpBBUserBehavior'=>array(
                'class'=>'application.components.phpBB.PhpBBUserBehavior',
                'forumDbConnection'=>'forumDb',
                'syncAttributes'=>array()
            ),
        );
    }

    public function updatePhoto($image_id){
        $user = User::model()->findByPk(Yii::app()->user->id);
        if($user){
            $user->image_id = $image_id;
            $request = $user->save();
            Yii::app()->user->setState('image', $user->getPathMainImage());
            return $request;
        } else {
            return false;
        }
    }
}