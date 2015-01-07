<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

/**
 * YOU CAN CONFIGURE SUB MODULES IN ADMIN MODULE
 *
 */
$config = array(

	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'language' => 'en',
    //'language' => 'ru',
	// project name
	'name'=>'FITBODY',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
        'application.models.activities.*',
		'application.components.*',
		'application.components.Dependency.*',
		'application.helpers.FileHelper',
		'application.helpers.FunctionHelper',
		'application.helpers.DateHelper',
		'application.modules.admin.modules.images.models.*',
	),

	'modules'=>array(

		'admin' => array(
			//  include sideBar widgets will be
			//  displayed in the left
			'sideBarWidgets' => array(

				array(
					'name' => 'admin.widgets.MenuConstructor',  //  required
					'params' => array()                         //  not required
				)
			),

			/**
			 * Build right menu
			 */
			'navRightMenuParams' => array(
				/**
				 * Set widget structure
				 */
				'structure' => array(
					//  structure can be as list or assoc array
					'language' => array(
						'a' => array(
							'title' => 'Language'
						)
					),
					//  set profile structure
					'profile' => array(
						'a' => array(
							'title' => 'Profile',
							'img' => array(
								'assetUrl' => true,
								'src' => '/img/user_avatar.png'
							)
						),
						'dropDown' => array(
							array(
								'a' => array(
									'title' => 'Log Out',
									'createUrl' => array(
										'route' => 'admin/default/logout'
									)
								)
							)
						)
					)
				)
			),

			'modules' => array(
				'users' => array(),
				'images' => array(),
				'seo' => array(),
                'registry' => array(),
                'place' => array(),
				'article' => array(),
				'exercise' => array(),
				'program' => array(),
                'club' => array(),
                'coach' => array(),
                'shop' => array(),
                'banner' => array(),
                'calculators' => array(),
                'book' => array(),
			),
		),
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'111111',
		),
	),

	// application components
	'components'=>array(
        'session' => array(
            'cookieParams' => array(
                'httponly' => true,
            ),
        ),
        'request'=>array(
            'class'=>'application.components.XHttpRequest',
            'noCsrfValidationRoutes'=>array(
                '^admin.*',
                '^ajax$'
            ),
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'CSRF_TOKEN'
        ),
        'user'=>array(
            //'class' => 'application.components.PhpBBWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/redirect-to-login'),
        ),
		'image'=>array(
			'class'=>'application.extensions.images.ImageComponent',
		),

		'files'=>array(
			'class'=>'application.extensions.files.FilesComponent',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'urlSuffix'=>'.html',
			'showScriptName' => false,
			'rules'=>array(
                'ajax' => 'ajax',
                'about' => 'site/about',
                'advertising' => 'site/advertising',
                'partnership' => 'site/partnership',
                'contacts' => 'site/contacts',

                //front article routes
                'news/page/<page:\d+>' => 'news/index',
                'news' => 'news/index',
                'news/<action>/<slug>/page/<page:\d+>' => 'news/<action>',
                'news/<action>/<slug>' => 'news/<action>',
                'article/<slug>' => 'article/index',

                //front exercises routes
                'exercises/<type:trx|with-weights>/page/<page:\d+>' => 'exercises/index',
                'exercises/<type:trx|with-weights>' => 'exercises/index',
                'exercises/<type:trx|with-weights>/<category>/page/<page:\d+>' => 'exercises/category',
                'exercises/<type:trx|with-weights>/<category>' => 'exercises/category',
                'exercise/<itemId\d+>'  => 'exercise',

                //front club routes
                'club/list/<category>'=> 'club/list',
                'club/list'           => 'club/list',
                'club/<slug>/about'   => 'club/about',
                'club/<slug>/price'   => 'club/price',
                'club/<slug>/coaches' => 'club/coaches',
                'club/<slug>/news'    => 'club/news',
                'club/<slug>/reviews' => 'club/reviews',
                'club/<slug>/article/<article_slug>' => 'club/article',
                'ajax-request/club/get-clubs'     => 'club/getNearestClubs',

                //front shop routes
                'shop/list/<category>'=> 'shop/list',
                'shop/list'           => 'shop/list',
                'shop/<slug>/about'   => 'shop/about',
                'shop/<slug>/news'    => 'shop/news',
                'shop/<slug>/reviews' => 'shop/reviews',
                'shop/<slug>/article/<article_slug>' => 'shop/article',
                'ajax-request/shop/get-shops'     => 'shop/getNearestShops',

                //front coaches routes
                'coaches/category/<categorySlug>/page/<page:\d+>' => 'coaches/index',
                'coaches/category/<categorySlug>' => 'coaches/index',
                'coaches/page/<page:\d+>' => 'coaches/index',
                'coaches' => 'coaches/index',

                //front activity routes
                'activity/page/<page:\d+>' => 'activity/index',
                'activity' => 'activity/index',

                //front coach routes
                'coach/<coachSlug>/news' => 'coach/news',
                'coach/<coachSlug>/news/<newsSlug>' => 'coach/news',
                'coach/<coachSlug>/video/page/<page:\d+>' => 'coach/video',
                'coach/<coachSlug>/video' => 'coach/video',
                'coach/<coachSlug>/reviews' => 'coach/reviews',
                'coach/<coachSlug>' => 'coach/index',

                //front banner routes
                'banner/<hash:[\d\w]{64}>' => 'banner/index',

                //front programs routes
                'program/<slug>' => 'program/index',
                'programs/category/<slug>/page/<page:\d+>' => 'programs/index',
                'programs/category/<slug>' => 'programs/index',
                'programs/page/<page:\d+>' => 'programs/index',
                'programs' => 'programs/index',

                //front books routes
                'books/download/<hash:[\d\w]{64}>' => 'books/download',
                'books/page/<page:\d+>' => 'books/index',
                'books' => 'books/index',
                'books/category/<slug>/page/<page:\d+>' => 'books/index',
                'books/category/<slug>' => 'books/index',

                //front calculators routes
                'calculator/<slug>' => 'calculator/index',
                'calculators' => 'calculators/index',

                //front search routes
                'search/<class:\w+>/page/<page:\d+>' => 'search/index',
                'search/<class:\w+>' => 'search/index',
                'search/page/<page:\d+>' => 'search/index',
                'search' => 'search/index',

                //auth front routes
                'confirmation/<hash:[\d\w]{64}>' => 'authorization/confirmation',
                'register' => 'authorization/register',
                'thanks' => 'authorization/thanks',
                'logout' => 'authorization/logout',
                'redirect-to-login' => 'authorization/redirectToLogin',
                'login-hash/<hash:[\d\w]{64}>' => 'authorization/loginByHash',
                'login/*' => 'authorization/login',
                'login' => 'authorization/login',
                'forgot-password' => 'authorization/forgotPassword',
                'forgot-password-success' => 'authorization/forgotPasswordInstructions',
                'facebook-login' => 'facebook/facebookLogin',
                'vk-login' => 'vk/vkLogin',

                //profile front routes
                'profile/<user_id:\d+>'                     => 'profile/profile/index',
                'profile/<user_id:\d+>/hidden'              => 'profile/profile/hide',

                'profile/<user_id:\d+>/goals'               => 'profile/goals/index',
                'profile/goals/add'                         => 'profile/goals/add',
                'ajax-request/profile/goals/edit'           => 'profile/goals/edit',
                'ajax-request/profile/goals/remove'         => 'profile/goals/remove',
                'ajax-request/profile/goals/get-list'       => 'profile/goals/getList',
                'ajax-request/profile/goals/get-block'      => 'profile/goals/getBlock',
                'ajax-request/profile/goals/remove_progress'=> 'profile/goals/removeProgress',
                'ajax-request/profile/goals/progress-add'   => 'profile/goals/progressAdd',

                'profile/<user_id:\d+>/progress'            => 'profile/progress/index',
                'profile/progress/add'                      => 'profile/progress/add',
                'profile/progress/edit/<progress_id:\d+>'   => 'profile/progress/edit',
                'profile/progress/upload/method/<method>/image/<imageId>'             => 'profile/progress/upload',

                'profile/<user_id:\d+>/program'             => 'profile/program/index',
                'profile/program/add'                       => 'profile/program/edit',
                'profile/program/edit/<program_id:\d+>'     => 'profile/program/edit',
                'ajax-request/profile/program/change-date'  => 'profile/program/changeDate',
                'ajax-request/profile/program/edit-note'    => 'profile/program/editNote',

                'profile/<user_id:\d+>/activity'            => 'profile/activity/index',

                'ajax-request/profile/photo/change-comments'=> 'profile/photo/comments',
                'profile/<user_id:\d+>/photo'               => 'profile/photo/index',
                'profile/photo/upload/method/<method>/image/<imageId>'             => 'profile/photo/upload',
                'profile/photo/delete/<imageId>'            => 'profile/photo/delete',
                'profile/<user_id:\d+>/photo/gallery'       => 'profile/photo/gallery',


                'profile/settings'                          => 'profile/settings/index',
                'profile/settings/upload/method/<method>/image/<imageId>'             => 'profile/settings/upload',
                'ajax-request/profile/settings/change-main-info'    => 'profile/settings/changeMainInfo',
                'ajax-request/profile/settings/change-profile-info' => 'profile/settings/changeProfileInfo',
                'ajax-request/profile/settings/change-password'     => 'profile/settings/changePassword',

                //admin routes
				'admin'=>'admin/default/index',
				'admin/login'=>'admin/default/login',
                'admin/city/<action:list|add|update>' => 'admin/city/city/<action>',

                'admin/exercise/body-part'=>'admin/exercise/BodyPart',
                'admin/exercise/body-part/list'=>'admin/exercise/BodyPart/list',
                'admin/exercise/body-part/update'=>'admin/exercise/BodyPart/update',
                'admin/exercise/body-part/add'=>'admin/exercise/BodyPart/add',

                //multiple upload exercise video
                'admin/exercise/uploadExerciseVideos/item/<itemId:\d+>'=>'admin/exercise/exercise/uploadExerciseVideos',
                'admin/exercise/exercise/uploadExerciseVideos/method/<method:\w+>/video/<videoId:\d+>'=>'admin/exercise/exercise/uploadExerciseVideos',
                'admin/exercise/exercise/UploadedMultipleVideos/<itemId:\d+>'=>'admin/exercise/exercise/UploadedMultipleVideos',
                // end multiple upload exercise video

                //single upload banner
                'admin/banner/banner/uploadBannerFiles/item/<itemId:\d+>'=>'admin/banner/banner/uploadBannerFiles',
                'admin/banner/banner/uploadBannerFiles/method/<method:\w+>/banner/<itemId:\d+>/'=>'admin/banner/banner/uploadBannerFiles',
                'admin/banner/banner/UploadedBanner/<itemId:\d+>'=>'admin/banner/banner/UploadedBanner',
                // end single upload banner

                //upload image
                'admin/<mdl>/<cntrl>/<act>/method/<method:\w+>/image/<imageId:\d+>'=>'admin/<mdl>/<cntrl>/<act>',
                'admin/<mdl>/<cntrl>/<act>/item/<itemId:\d+>'=>'admin/<mdl>/<cntrl>/<act>',
                'admin/<mdl>/<cntrl>/<act>/'=>'admin/<mdl>/<cntrl>/<act>',
                ////////////////////////////////////////////////////////////
                //single upload
                'admin/<mdl>/<cntrl>/UploadedSingleImages/<itemId:\d+>'=>'admin/<mdl>/<cntrl>/UploadedSingleImages',
                'admin/<mdl>/<cntrl>/UploadedSingleImages/'=>'admin/<mdl>/<cntrl>/UploadedSingleImages',
                //multiple upload
                'admin/<mdl>/<cntrl>/UploadedMultipleImages/<itemId:\d+>'=>'admin/<mdl>/<cntrl>/UploadedMultipleImages',
                //end upload image


                // REST patterns
                array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
                array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
                array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),

			),
		),

		'assetManager' => array(
			'forceCopy' => YII_DEBUG
		),

		//
		'authManager' => array(
			// Будем использовать свой менеджер авторизации
			'class' => 'PhpAuthManager',
			// Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
			'defaultRoles' => array('guest'),
		),

		'errorHandler'=>array(
			// установка экшена для ошибок
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
			    array(
					'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
					'ipFilters'=>array('127.0.0.1','192.168.1.215'),
					'enabled' => (isset($_GET['debug'])&&!$_GET['debug'])?false:true,
				),
				array(
					'class' => 'CWebLogRoute',
					'categories' => 'application',
					'levels'=>'error, warning, trace, profile, info',
				),
			),

		),

		'static' => array(
			'class'  => 'application.modules.admin.modules.registry.StaticComponent'
		),
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType'=>'php',
            'viewPath' => 'application.views.emails',
        ),
        'phpBB'=>array(
            'class'=>'application.extensions.phpBB.phpBB',
            'path'=>'webroot.forum',
        ),
        'cache' => array(
//            'class' => 'CApcCache',
            'class' => 'system.caching.CFileCache',
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
        // TODO:: credentials for production server
        'twitter' => 'fitbody',
        'facebook' => array(
            '554.loc' => array(
                'appId' => '709272145798842',
                'appSecret' => '979296005f40cd65b84aca876705dd23',
            ),
            '554.repogit.com' => array(
                'appId' => '717154255010631',
                'appSecret' => '8c5425346ce7999d0e38163be7b5ba61',
            ),
        ),
        'vk' => array(
            '554.loc' => array(
                'appId' => '4438159',
                'appSecret' => 'bekACaHTGioeA73nq8ME',
            ),
            '554.repogit.com' => array(
                'appId' => '4457565',
                'appSecret' => 'vj2GZtgGN3j432Ak0xw5',
            ),
        ),
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
        //'phpbb_security_user' => '_phpbb_wASDawAWASd__AS_asdkk@3412@#@_user_',
		//  system languages
		'languages' => array(
			//  key -> valid language
			//  value -> icon class
			/*'en_us' => array(
				'title' => 'Eng',
				'icon' => 'flag-gb'
			),
			'ru_ru' => array(
				'title' => 'Ru',
				'icon' => 'flag-ru'
			)*/
            'ru' => array(
                'title' => 'Ru',
                'icon' => 'flag-ru'
            ),
            'en' => array(
                'title' => 'Eng',
                'icon' => 'flag-gb'
            ),
		)
	),
);
if(defined("YII_DEBUG") and YII_DEBUG)
	$config['import'][] = 'application.extensions.debug.*';
require_once 'main.local.php';
return $config;