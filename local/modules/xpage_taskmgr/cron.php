<?php
if(php_sapi_name()==='cli')
{
    define("NOT_CHECK_PERMISSIONS",true);
}
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['TASKMGR_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/xpage_taskmgr/';

if (CModule::IncludeModule('xpage_taskmgr'))
{
    \Xpage\Taskmgr\Watcher::run();
}
