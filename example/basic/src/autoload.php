<?php
namespace Magnus {
	spl_autoload_register(function ($className) {

		$className  = str_replace("\\", '/', $className);
		$packageDir = dirname(__DIR__, 4);
		$vendorsDir = dirname(__DIR__) . '/vendor';

		echo $vendorsDir . '/' . $className . '.php';

		if (file_exists($packageDir . '/' . $className . '.php')) {
			require_once $packageDir . '/' . $className . '.php';
		} else if (file_exists(__DIR__ . '/' . $className . '.php')) {
			require_once __DIR__ . '/' . $className . '.php';
		} else if (file_exists($vendorsDir . '/' . $className . '.php')) {
			require_once $vendorsDir . '/' . $className . '.php';
		}
		
	});
}