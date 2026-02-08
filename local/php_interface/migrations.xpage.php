<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

if ($filePath = getLocalPath('modules/xpage.core/include/migrations_cfg.php')) {
    return include($_SERVER['DOCUMENT_ROOT'] . $filePath);
}