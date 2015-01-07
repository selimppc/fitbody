<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/6/12
 * Time: 11:15 AM
 * To change this template use File | Settings | File Templates.
 */
return array(
	'guest' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Guest',
		'bizRule' => null,
		'data' => null
	),
	'user' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'User',
		'children' => array(
			'guest', // унаследуемся от гостя
		),
		'bizRule' => null,
		'data' => null
	),
	'moderator' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Moderator',
		'children' => array(
			'user',          // позволим модератору всё, что позволено пользователю
		),
		'bizRule' => null,
		'data' => null
	),
	'administrator' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Administrator',
		'children' => array(
			'moderator',         // позволим админу всё, что позволено модератору
		),
		'bizRule' => null,
		'data' => null
	),
	'developer' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Developer',
		'children' => array(
			'administrator',         // позволим разработчику всё, что позволено модератору
		),
		'bizRule' => null,
		'data' => null
	)
);