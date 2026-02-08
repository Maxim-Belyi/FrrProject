<?php
define('NEED_AUTH',true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/** @var CMain $APPLICATION */
$APPLICATION->SetTitle("Планировщик заданий");
?>
<?php
$APPLICATION->IncludeComponent("xpage:taskmgr", "", array());
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
