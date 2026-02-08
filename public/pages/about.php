<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("О нас");
$APPLICATION->AddChainItem('О нас');

$APPLICATION->IncludeComponent("xpage:blank", "page-about");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");