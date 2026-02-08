<?php

	namespace Controllers;

	use Bitrix\Main\Context;
	use Xpage\Core\Controller\Base;


	class FormController extends Base
	{
		private static self $instance;

		public function __construct()
		{
			parent::__construct();
		}

		public static function getInstance(): self
		{
			if (empty(self::$instance))
			{
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function configureActions(): array
		{
			return [
				'addCallbackForm' => parent::getGuestPreFilters(true),
			];
		}

		public function addCallbackFormAction()
		{
			$request = Context::getCurrent()->getRequest();
			//обработка запроса
		}
	}