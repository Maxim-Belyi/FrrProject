<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/xpage_autoloader.php';

	use Bitrix\Main\Routing\RoutingConfigurator;
	use Bitrix\Main\Routing\Controllers\PublicPageController;


	return function (RoutingConfigurator $routes)
	{
		//первый вариант
		$routes->get('/about/', new PublicPageController('/pages/about.php'));
		//в данном случае кеш сбрасывать необязательно

		//второй вариант
		//NewsHandlers::registerHandlers($routes);
		//регистрируем роуты в классе определенной сущности, в данном случае новостей
		//обращаю внимание, что при изменении кода роута надо сбросить кеш
	};

