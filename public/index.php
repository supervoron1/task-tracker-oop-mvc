<?php
session_start();
include realpath("../config/config.php");
include realpath("../engine/Autoload.php");

use app\engine\{Autoload, Render, Request};

spl_autoload_register([new Autoload(), 'loadClass']);

$request = new Request();

$controllerName = $request->getControllerName();
$actionName = $request->getActionName();

$controllerClass = CONTROLLER_NAMESPACE . ucfirst($controllerName) . "Controller";
if (class_exists($controllerClass)) {
    $controller = new $controllerClass(new Render());
    $controller->runAction($actionName);
} else die("404 - Controller missing");
