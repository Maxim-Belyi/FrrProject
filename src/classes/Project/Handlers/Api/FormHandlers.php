<?php

	namespace Project\Handlers\Api;

	use Bitrix\Main\Routing\RoutingConfigurator;

	//пример обработчика. директория Controllers вынесена именно в classes,
	//потому что битрикс на более высоком уровне вложенности не видит обработчики
    class FormHandlers
	{
		private static string $prefix = GENERAL_PREFIX . '/form';

		public static function registerHandlers(RoutingConfigurator $routes): void
		{
			$routes->prefix(self::$prefix)->group(function (RoutingConfigurator $routes)
			{
//				$routes->post('callback/', [FormController::class, 'addCallbackFormAction']);
			});
		}
	}