<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/28/12
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */
abstract class BaseCommand extends CConsoleCommand {

	/**
	 * @param $message
	 */
	protected function error($message, $status = 1) {
		fputs(STDERR, $message . "\n");
		Yii::app()->end($status);
	}

	/**
	 * @param $property
	 * @param null $default
	 * @param bool $dieOnNotexists
	 *
	 * @return mixed|null
	 */
	protected function getGlobalProperty($property, $default = null, $dieOnNotexists = true) {
		if (property_exists($this, $property) && $this->$property !== null) {
			return $this->$property;
		}
		if ($dieOnNotexists) {
			$this->error(sprintf("Property '%s' not defined or is null", $property));
		}
		return $default;
	}

	/**
	 * @return string
	 */
	public function getHelp() {
		$help ='Usage: '.$this->getCommandRunner()->getScriptName().' '.$this->getName() . ' [action] [options]';
		$class = new ReflectionClass(get_class($this));
		$help .= "\n\nGlobal properties:\n";
		foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			$help .= "\n";
			if ($property->getName() == 'defaultAction') {
				continue;
			}
			$value = $property->getValue($this);
			if (is_null($value)) {
				$value = 'null';
			} else if (is_array($value)) {
				$value = 'array';
			}

			$comment = $property->getDocComment();
			if ($comment) {
				$help .= "\t";
				$help .= $comment ."\n";
			}
			$help .= "\t--" . $property->getName() . ', default: ' . $value . "\n";
		}
		$options=$this->getOptionHelp();
		if(empty($options))
			return $help;
		if(count($options)===1)
			return $help.' '.$options[0];
		$help.="\nActions:\n";
		foreach($options as $option) {
			$chunk = explode(' ', $option);
			$method = 'action' . ucfirst($chunk[0]);
			$method = $class->getMethod($method);
			$comment = $method->getDocComment();
			if ($comment) {
				$help .= "\t";
				$help .= $comment . "\n";
			}
			$help.= "\t" . $option."\n";
			$method = 'helpFor' . ucfirst($chunk[0]);
			if (method_exists($this, $method)) {
				$this->{$method}($help);
			}
			$help .= "\n";
		}
		return $help;
	}
}