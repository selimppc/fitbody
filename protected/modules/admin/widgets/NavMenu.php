<?php
class NavMenuComposite extends CompositeComponent {

	private $position = 0;
	private $children = array();

	/**
	 * @param CompositeComponent $component
	 *
	 * @return NavMenuComposite
	 */
	public function add(CompositeComponent $component) {
		$this->children[$component->id] = $component;
		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return bool|mixed
	 */
	public function removeByID($id) {
		$menuItem = $this->get($id);
		if ($menuItem) {
			return $this->remove($menuItem);
		}
		return false;
	}

	/**
	 * @param CompositeComponent $component
	 *
	 * @return mixed
	 */
	public function remove(CompositeComponent $component) {
		if (array_key_exists($component->id, $this->children)) {
			unset($this->children[$component->id]);
		}
		return true;
	}

	/**
	 * @param $id
	 *
	 * @return NavMenuComposite || null
	 */
	public function get($id) {
		return array_key_exists($id, $this->children) ? $this->children[$id] : null;
	}

	public function getChildren() {
		return $this->children;
	}

	/**
	 * @return bool
	 */
	public function childrenExists() {
		return !empty($this->children);
	}

	/**
	 * Get current item
	 * Use in cycles
	 *
	 * @return mixed
	 */
	public function current() {
		$el = current($this->children);
		next($this->children);
		return $el;
	}
}

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/9/12
 * Time: 1:02 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Top center menu builder
 *
 * ITEM CAN CONTAINS NEXT FIELDS
 *
 * ITEM = $ITEM
 *
 *      - href      - url or javascript code. not required
 *      - icon      - is this first level your can set class as icon see http://twitter.github.com/bootstrap/base-css.html#icons
 *      - title     - will be displayed as caption and as link title
 *      - events    - array, key => valid event name, value = callback. EXAMPLE: array('onclick' => 'alert(\'click\');', 'onmousedown' => 'alert(\'mouse down\')')
 *
 * Example of use:
 *
 * a) from PHP
 *    1) Using NavMenuComposite : read http://ru.wikipedia.org/wiki/Composite
 *       example:
 *          Yii::import('admin.widgets.NavMenu');       //  import widget
 *          $map = NavMenu::newItem('map');             //  create map - items container, map is ID
 *          $user = NavMenu::newItem('user', $ITEM);    //  set user as ID and $ITEM see declaration above
 *          //  now you can add user to map
 *          $map->add($user);                           //  add return $this and you can use $map->add($user)->add($userEdit);
 *          $user->add(NavMenu::newItem('userEdit', array(
 *              'title' => 'User edit',
 *              'href' => 'javascript:alert(\'User edit\');'
 *          )));
 *          NavMenu::$staticMap = $map;
 *
 *    2) Using array
 *       example:
 *          Yii::import('admin.widgets.NavMenu');
 *          NavMenu::$staticMap = array(
 *              array(
 *                  'title' => 'User',
 *                  'children' => array(
 *                      array(
 *                          'title' => 'User Edit',
 *                          'children' => array()       //  children not require parameter and can be empty or not exists in map
 *                      )
 *                  )
 *              ),
 *              array('title' => 'Blog')
 *          );
 *
 * b) Configuring via Module Settings will be like via php using arrays.
 *    Module property: navMenuParams
 *    EXAMPLE:
 *    'navMenuParams' => array(
 *        'map' => structure    // see structure above
 *    )
 *
 * IF YOU SET SETTING VIA MODULE AND VIA PHP.
 * PHP PRIORITY HIGHTER THAN MODULE
 * USE STATIC PROPERTY TO CHANGE THIS CODE:
 *
 *      NavMenu::$priorityStatic = false;
 */
class NavMenu extends CWidget {

	/**
	 * @var bool
	 */
	public static $priorityStatic = true;

	/**
	 * Type can be
	 *      1) NavMenuComposite
	 *      2) array('title' => '', 'url' => '', 'children' => array())
	 *
	 * @var NavMenuComposite || array
	 */
	public static $staticMap;

	/**
	 * @var array
	 */
	public $map;

	/**
	 * @var NavMenuComposite || array
	 */
	protected $_map;

	/**
	 * @var
	 */
	protected $_menu;

	/**
	 * @param $id
	 * @param $params
	 *
	 * @return NavMenuComposite
	 */
	public static function newItem($id, $params = array()) {
		return new NavMenuComposite($id, $params);
	}

	/**
	 * Open tag and set options
	 *
	 * And set result into _menu
	 *
	 * @param string $tag
	 * @param array $options
	 */
	protected function open($tag, $options = array()) {
		$this->_menu .= CHtml::openTag($tag, $options);
	}

	/**
	 * @param string $tag
	 */
	protected function close($tag) {
		$this->_menu .= CHtml::closeTag($tag);
	}

	/**
	 * @param string $tag
	 * @param array $options
	 * @param bool $content
	 */
	protected function tag($tag, $options, $content = false) {
		$this->_menu .= CHtml::tag($tag, $options, $content);
	}

	/**
	 * Open li element end paste result into _menu
	 *
	 * @param $isFirst
	 */
	protected function openLi($isFirst) {
		if ($isFirst) {
			$this->open('li', array(
				'class' => 'dropdown'
			));
		} else {
			$this->open('li');
		}
	}

	/**
	 * Draw a element
	 *
	 * @param $isFirst
	 * @param $item
	 * @param $childrenExists
	 */
	protected function tagA($isFirst, $item, $childrenExists) {
		$options = array(
			'title' => $item['title'],
			'href' => $item['href']
		);
		$options = array_merge($options, $item['events']);
		$i = '';
		if ($isFirst && $childrenExists) {
			$options['data-toggle'] = 'dropdown';
			$options['class'] = 'dropdown-toggle';
			$i = CHtml::tag('i', array(
				'class' => isset($item['icon']) ? $item['icon'] : 'icon-list-alt icon-white'
			), '');
		}
		$this->open('a', $options);
		//  set icon
		$this->_menu .= $i;
		//  set title
		$this->_menu .= $item['title'];
		//  set caret
		if ($childrenExists) {
			$this->tag('b', array(
				'class' => $isFirst ? 'caret' : 'caret-right'
			), '');
		}
		$this->close('a');
	}

	/**
	 * @param $menu
	 */
	protected function parseMenuStructure($menu) {
		$this->_menu = CHtml::openTag('ul', array(
			'class' => 'nav'
		));
		if ($menu instanceof CompositeComponent) {
			while ($item = $menu->current()) {
				$this->parseStructureComposite($item, true);
			}
		} else {
			foreach ($menu as $item) {
				$this->parseStructureArray($item, true);
			}
		}
		$this->_menu .= CHtml::tag('li');
		$this->_menu .= CHtml::closeTag('ul');
	}

	/**
	 * Basic configuration of menu item html
	 *
	 * @param array $item
	 * @param $isFirst
	 *
	 * @return array
	 */
	protected function parseItem(array $item, $isFirst) {
		$item['href'] = isset($item['href']) ? $item['href'] : 'javascript:;';
		$item['title'] = isset($item['title']) ? $item['title'] : '';
		$item['events'] = isset($item['events']) ? (array)$item['events'] : array();
		return $item;
	}

	/**
	 * Parse array structure
	 *
	 * @param array $array
	 * @param bool $isFirst
	 */
	protected function parseStructureArray(array $array, $isFirst = false) {
		$this->openLi($isFirst);
		$options = $this->parseItem($array, $isFirst);
		$childrenExists = isset($array['children']) && !empty($array['children']);
		$this->tagA($isFirst, $options, $childrenExists);
		if ($childrenExists) {
			$this->open('ul', array(
				'class' => 'dropdown-menu'
			));
			foreach ($array['children'] as $item) {
				$this->parseStructureArray($item);
			}
			$this->close('ul');
		}
		$this->close('li');
	}

	/**
	 * Parse composite object
	 *
	 * @param NavMenuComposite $menu
	 * @param bool $isFirst
	 */
	protected function parseStructureComposite($menu, $isFirst = false) {
		$this->openLi($isFirst);
		$options = $this->parseItem($menu->getParams(), $isFirst);
		$childrenExists = $menu->childrenExists();
		$this->tagA($isFirst, $options, $childrenExists);
		if ($childrenExists) {
			$this->open('ul', array(
				'class' => 'dropdown-menu'
			));
			foreach ($menu->getChildren() as $id => $children) {
				$this->parseStructureComposite($children);
			}
			$this->close('ul');
		}
		$this->close('li');
	}

	/**
	 *
	 * Draw
	 */
	public function run() {
		if ($this->map && self::$staticMap) {
			$this->_map = self::$priorityStatic ? self::$staticMap : $this->map;
		} else if ($this->map && !self::$staticMap) {
			$this->_map = & $this->map;
		} else if (!$this->map && self::$staticMap) {
			$this->_map = self::$staticMap;
		} else {
			return;
		}
		$this->parseMenuStructure($this->_map);
		echo $this->_menu;
	}
}