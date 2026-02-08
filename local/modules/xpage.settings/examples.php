<?php

use Bitrix\Main\Loader;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\Fields\SettingString;
use Xpage\Settings\Models\SettingFieldType;
use Xpage\Settings\SettingBuilder;
use Xpage\Settings\SettingTypeBuilder;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

Loader::IncludeModule("xpage.settings");

echo Setting::getString('TITLE');
echo Setting::getBool('is_gay');
echo Setting::getInt('year');
echo Setting::getFloat('price');
echo Setting::getFile('file');


//Миграции типо

$SettingTypeBuilder = new SettingTypeBuilder();
$SettingTypeBuilder
	->setCode('test')
	->setHandler(SettingString::class)
	->setName('testik')
	->save();

$SettingBuilder = new SettingBuilder();
$type = SettingFieldType::getByCode('integer');
$SettingBuilder
	->setCode('integer')
	->setFieldType($type)
	->setName('Число но строка')
	->setValue('Что-то написано')
	->save();