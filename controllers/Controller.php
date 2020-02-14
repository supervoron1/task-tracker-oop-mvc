<?php


namespace app\controllers;

use app\engine\Render;
use app\interfaces\IRenderer;

abstract class Controller
{
	private $action;
	private $defaultAction = 'index';
	private $layout = 'main';
	private $useLayout = true;
	private $renderer;


	public function __construct(IRenderer $renderer)
	{
		$this->renderer = $renderer;
	}


	public function runAction($action = null)
	{
		$this->action = $action ?: $this->defaultAction;
		$method = "action" . ucfirst($this->action);
		if (method_exists($this, $method)){
			$this->$method();
		}
	}

	public function render($template, $params = [])
	{
		if ($this->useLayout) {
			return $this->renderTemplate("layouts/{$this->layout}", [
				'menu' => $this->renderTemplate('menu', $params),
				'content' => $this->renderTemplate($template, $params)
			]);
		} else {
			return $this->renderTemplate($template, $params);
		}
	}

	public function renderTemplate($template, $params = [])
	{
		return $this->renderer->renderTemplate($template, $params);
	}

	public function mb_ucfirst($str, $encoding = 'UTF-8')
	{
		$str = mb_ereg_replace('^[\ ]+', '', $str);
		$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
			mb_substr($str, 1, mb_strlen($str), $encoding);
		return $str;
	}

	public function unique_multidim_array($array, $key) {
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	}

}
