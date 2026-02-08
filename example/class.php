<?php

	class XpageMenuSectionComponent extends CBitrixComponent
	{
		public function __construct($component = null)
		{
			parent::__construct($component);
		}

		public function onPrepareComponentParams($arParams)
		{
			//это примеры параметров
			return [
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"] ? $arParams["IBLOCK_TYPE"] : false,
				"CACHE_TIME"  => $arParams["CACHE_TIME"] ? $arParams["CACHE_TIME"] : 3600,
			];
		}

		public function executeComponent()
		{
			\Bitrix\Main\Loader::includeModule("iblock");

			$this->arResult['ITEMS'] = $this->someFunc();// при формировании $arResult можно использовать любые методы
			//в $arResult можно добавлять любые ключи
			$this->includeComponentTemplate();// обязательно подключаем шаблон!
		}

		private function someFunc(): array
		{
			return [];
		}

	}
