<?php
if (is_dir($_SERVER['DOCUMENT_ROOT'].'/local/modules/xpage.settings')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/xpage.settings/admin/xpage_settings_edit.php';
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/xpage.settings/admin/xpage_settings_edit.php';
}

