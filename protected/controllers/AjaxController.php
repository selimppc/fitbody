<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 07.10.13
 * Time: 16:52
 * To change this template use File | Settings | File Templates.
 */
class AjaxController extends Controller {

	const DATA_TYPE_ENCODED     = 1;
	const DATA_TYPE_UNKNOWN     = 0;
	const DATA_TYPE_NOTENCODED  = -1;

	private $controller;

	public function actionIndex() {
        Yii::import('application.modules.admin.modules.registry.controllers.RegistryController');
		try {
			list($controller,$method,$data) = self::checkParams();
			self::runController($controller,$method,$data);
		} catch (CException $e) {
			self::returnError($e);
		}
	}

	private function runController($controller,$method,$data) {
		Yii::import($controller,true);
		$controllerName = preg_replace('/.+(\.|\/)(\w+)/','$2',$_POST['controller']);
		$this->controller = new $controllerName('');
		if (!($this->controller instanceof IAjax)) {
			throw new CException('Controller not implemented from interface IAjax');
		}
		$map = $this->controller->getSuccess();
		if (!is_array($map) || empty($map))
			throw new CException('Invalid rules map');

		if(!array_key_exists($method,$map))
			throw new CException('Access denied');

		$this->controller->init();
		Yii::app()->getSession()->open();
//		$webUser = new WebUser();
		$role = Yii::app()->user->role;
		if(is_array($map[$method])) {
			if(in_array($role,$map[$method]))
				throw new CException('Access denied');
		} else {
			if(!is_numeric($map[$method]))
				throw new CException('Access denied');
			if($role < $map[$method])
				throw new CException('Access denied');
		}
		if (!method_exists($this->controller, $method)) {
			throw new CException('Invalid method');
		}
		$reflection = new ReflectionMethod($this->controller, $method);
		$paramsCount = $reflection->getNumberOfParameters();
		if($paramsCount==0 || ($paramsCount==1 && !is_array($data))) {
			$data = $this->controller->{$method}($data);
			if($data !== null) {
				self::setResponse($data);
			}
		} else {
			$varsArray = array();
			foreach($reflection->getParameters() as $parameter) {
				$name = $parameter->getName();

				if(isset($data[$name]))
					$varsArray[] = $data[$name];
				elseif($parameter->isDefaultValueAvailable()) {
					$varsArray[] = $parameter->getDefaultValue();
				} else
					throw new CException('Array keys do not match the names of method parameters');
			}
			$data = call_user_func_array(array($this->controller,$method),$varsArray);
			if($data !== null) {
				self::setResponse($data);
			}
		}
		throw new CException('No response to send');
	}

	/**
	 * check exists params controller and method
	 * @throws CException
	 */
	private function checkParams() {
		$controller = Yii::app()->request->getParam('controller',false);
		$method     = Yii::app()->request->getParam('method',false);
		$data       = self::parseData(Yii::app()->request->getParam('data',null));
		if(!$controller || !$method) {
			throw new CException('Invalid params');
		}
		if(preg_match('/admin\./',$controller)) {
			Yii::import('admin.components.*');
		}
		return array($controller,$method,$data);
	}


	private function setResponse($data) {
		echo CJavaScript::jsonEncode(array(
			'error'     => false,
			'errorMsg'  => '',
			'data'      => $data
		));
		Yii::app()->end();
	}

	/**
	 * return error
	 *
	 * @param $error
	 */
	private function returnError($error) {
		echo CJavaScript::jsonEncode(array(
			'error'     => true,
			'errorMsg'  => $error->getMessage()
		));
		Yii::app()->end();
	}

	/**
	 * parse data
	 *
	 * @param $data
	 * @return mixed|null|string
	 */
	private function parseData(& $data) {
		switch ((int)Yii::app()->request->getParam('isEncodedData',self::DATA_TYPE_NOTENCODED)) {
			case self::DATA_TYPE_NOTENCODED:
				break;

			case self::DATA_TYPE_ENCODED:
				$data = CJavaScript::jsonDecode($data, true);
				break;

			case self::DATA_TYPE_UNKNOWN:
				if (empty($data)) {
					$data = null;
				} else {
					if (is_string($data)) {
						if ($data[0] == '{' && $data[strlen($data) - 1] == '}') {
							$data = CJavaScript::jsonDecode($data, true);
						}
					}
				}
				break;
		}
		return $data;
	}
}