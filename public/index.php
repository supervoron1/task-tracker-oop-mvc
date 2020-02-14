<?php
session_start();
include realpath("../config/config.php");
include realpath("../engine/Autoload.php");

use app\engine\{Autoload, Render, Request};

spl_autoload_register([new Autoload(), 'loadClass']);

try {
	$request = new Request();
	$controllerName = $request->getControllerName();
	$actionName = $request->getActionName();

	$controllerClass = CONTROLLER_NAMESPACE . ucfirst($controllerName) . "Controller";
	if (class_exists($controllerClass)) {
		$controller = new $controllerClass(new Render());
		$controller->runAction($actionName);
	} else {
		throw new Exception("Controller missing", 404);
	}
} catch (\PDOException $e) {
	var_dump($e->getMessage());
} catch (\Exception $e) {
	var_dump($e);
}
