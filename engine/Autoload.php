<?php

namespace app\engine;

class Autoload
{
	// Автозагрузчик классов
	public function loadClass($className)
	{
		$fileName = realpath(str_replace(['app', '\\'], ['..', DS], $className) . ".php");

		if (file_exists($fileName)) {
			include $fileName;

		}


	}
}
