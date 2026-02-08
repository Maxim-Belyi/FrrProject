//обязательно подключаем модуль

\Bitrix\Main\Loader::requireModule("xpage_settings");



echo \Xpage\Settings\Abstracts\Setting::getString('TITLE');

echo \Xpage\Settings\Abstracts\Setting::getBool('is_oleg');

echo \Xpage\Settings\Abstracts\Setting::getInt('year');

echo \Xpage\Settings\Abstracts\Setting::getFloat('price');

//вернет идентификатор файла
echo \Xpage\Settings\Abstracts\Setting::getFile('file');
