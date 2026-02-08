<?php
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	{
		die('gtfo');
	}

	if (!defined('FROM_XPAGE_OFFICE'))
	{
		define('FROM_XPAGE_OFFICE', $_SERVER['REMOTE_ADDR'] === '94.181.33.167');
	}
	define('LOCAL_DEPLOYMENT', getenv('ENVIRONMENT') === 'dl');

//Автолоад классов из папки /local/php_interface/classes
	\Bitrix\Main\Loader::registerNamespace("Xpage", $_SERVER['DOCUMENT_ROOT'] . "/src/classes/Xpage");
	\Bitrix\Main\Loader::registerNamespace("Project", $_SERVER['DOCUMENT_ROOT'] . "/src/classes/Project");
	try
	{
		/*Здесь регистрируй события*/
		\Xpage\Local::registerEventsHandlers();
	}
	catch (\Throwable $e)
	{
		\Xpage\Tools::log($e, 'init', 'errors');
	}

	//изначально проект закрывает контент ото всех
	//\Xpage\Tools::closeByIp();


