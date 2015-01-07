<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/26/12
 * Time: 1:49 PM
 * To change this template use File | Settings | File Templates.
 */
class Pagination extends CPagination {

	public function createPageUrl($controller,$page) {
		$params=$this->params===null ? $_GET : $this->params;
		if($page>0) // page 0 is the default
			$params[$this->pageVar]=$page+1;
		else {
			//unset($params[$this->pageVar]);
			$params[$this->pageVar] = 1;
		}
		return $controller->createUrl($this->route,$params);
	}
}
