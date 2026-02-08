<?php
	spl_autoload_register(function ($class): void
	{
		$fullPath     = $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes';
		$clearedClass = str_replace('\\', '/', $class);
		$classPath    = $fullPath . '/' . $clearedClass . '.php';
		if (!file_exists($classPath))
		{
			return;
		}
		include_once $classPath;
	});