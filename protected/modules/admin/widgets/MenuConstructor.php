<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/8/12
 * Time: 3:22 PM
 * To change this template use File | Settings | File Templates.
 */
class MenuConstructor extends CWidget {

	protected $moduleID = 'admin';
	protected $menu = '';
	protected $moduleStack = array();


	protected function getHeaderTemplate() {
		return <<<TEMPLATE
<div class="accordion-group">
	<div class="accordion-heading {class.heading}">
		<a href="#{id}" module="{id}" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
			<i class="{icon}"></i> {title}
		</a>
	</div>
	<div class="accordion-body {class.body}" id="{id}">
		<div class="accordion-inner">
			<ul class="nav nav-list">
TEMPLATE;
	}

	protected function getFooterTemplate() {
		return <<<TEMPLATE
		</ul>
		</div>
	</div>
</div>
TEMPLATE;
	}

	protected function parseBlockTemplate($template, array $vars) {
		foreach ($vars as $k => $value) {
			$template = str_replace('{' . $k . '}', $value, $template);
		}
		return $template;
	}

	/**
	 * Check if current user have access to module with his role
	 *
	 * @param array $allowedRoles
	 * @return bool
	 */
	protected function isAllowed(array $allowedRoles) {
		return in_array(Yii::app()->user->role, $allowedRoles);
	}

	protected function isItemActive(array $item, $module) {
		if (empty($item['url'])) {
			return false;
		}
		if (strpos(Yii::app()->request->getRequestUri(), '/admin/' . implode($this->moduleStack, '/') . '/' . $item['url']) === 0) {
			return true;
		}
		return false;
	}

	protected function parseItem(array $item, &$container, $module) {
		if (array_key_exists('allowedRoles', $item) && !$this->isAllowed($item['allowedRoles'])) {
			//  current user not in allowed roles
			return;
		}
		if (array_key_exists('exceptRoles', $item) && $this->isAllowed($item['exceptRoles'])) {
			//  current user in except role
			return;
		}
		if (array_key_exists('header', $item) && $item['header']) {
			$li = '<li class="nav-header">' . Yii::t('admin', $item['title']) . '</li>';
		} else if ($this->isItemActive($item, $module)) {
			$url = Yii::app()->createUrl('admin/' . implode($this->moduleStack, '/') . '/' . $item['url']);
			$li = '<li class="active"><a href="'.$url.'" is-active="true">' . Yii::t('admin', $item['title']) . '</a></li>';
		} else {
			$url = Yii::app()->createUrl('admin/' . implode($this->moduleStack, '/') . '/' . $item['url']);
			$li = '<li><a href="' . $url . '">' . Yii::t('admin', $item['title']) . '</a></li>';
		}
		array_push($container, $li);
	}

	/**
	 * @param $module AdminModule
	 * @param $moduleID string
	 * @param array $moduleConfig
	 */
	protected function parseModule($module, $moduleID, array $moduleConfig) {
		if (array_key_exists('allowedRoles', $moduleConfig) && !$this->isAllowed($moduleConfig['allowedRoles'])) {
			return;
		}
		$modules = $module->getModules();
		if (empty($module->menuItems) && empty($modules)) {
			return;
		}
		array_push($this->moduleStack, $moduleID);
		$li = array();
		foreach ($module->menuItems as $item) {
			$this->parseItem($item, $li, $module);
		}
		$this->menu .= implode('', $li);
		$this->eachSubModulesOf($moduleID, $module);
		array_pop($this->moduleStack);
	}

	/**
	 * @param $module
	 * @param $mID
	 * @param $moduleConfig
	 * @return array
	 */
	protected function fetchBlockVars($module, $mID, $moduleConfig) {
		$vars = array(
			'id' => 'module_' . $mID,
			'title' => ($module->getModule($mID)->title) ? Yii::t('admin',$module->getModule($mID)->title) : Yii::t('admin', ucfirst($mID)),
			'icon' => $module->getModule($mID)->icon
		);
		$vars['class.heading'] = '';
		$vars['class.body'] = 'collapse';
		return $vars;
	}

	protected function eachSubModulesOf($moduleID, $_module = null) {
		$module = $_module === null ? Yii::app()->getModule($moduleID) : $_module;
		foreach ($module->getModules() as $mID => $moduleConfig) {
			if($this->isAllowed($module->getModule($mID)->allowedRoles)) {
				if ($moduleID == 'admin' && $_module === null) {
					$this->menu .= $this->parseBlockTemplate($this->getHeaderTemplate(), $this->fetchBlockVars($module, $mID, $moduleConfig));
				}
				$this->parseModule($module->getModule($mID), $mID, $moduleConfig);
				if ($moduleID == 'admin' && $_module === null) {
					$this->menu .= $this->getFooterTemplate();
				}
			}
		}
	}

	protected function constructMenu() {
		// bind menu elements
		$this->eachSubModulesOf('admin');
		return $this->menu;
	}

	public function run() {
		$js = 'var module = "'.Yii::app()->controller->module->name.'"; ';
		$js .=<<<JS
var \$currentItem = $('li a[is-active=true]');
if (\$currentItem) {
	var \$accordionBody = \$currentItem.parents('.accordion-body:first').removeClass('collapse').addClass('in collapse');
	\$accordionBody.prev().addClass('sdb_h_active');
	$('a[module="module_'+module+'"]').addClass('active');
	$('.accordion-body.collapse').each(function () {
		if(!$(this).find('li').length) {
			var a = $(this)
				.prev()
				.find('a');
				a
				.attr('data-toggle','')
				.data('toggle','')
				.attr('href','/admin/'+a.attr('href').replace(/#module_/,''));
		}
	})

}
JS;
		// added JavaScript script in page
		Yii::app()->clientScript->registerScript($this->getId(), $js);
		// echo menu html
		echo '<div id="side_accordion" class="accordion">' . $this->constructMenu() . '</div>';
	}
}
