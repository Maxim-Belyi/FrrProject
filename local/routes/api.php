<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/xpage_autoloader.php';

	use Bitrix\Main\Routing\RoutingConfigurator;

	const GENERAL_PREFIX = 'api';

	return function (RoutingConfigurator $routes)
	{
//		FormHandlers::registerHandlers($routes) пример
	};

