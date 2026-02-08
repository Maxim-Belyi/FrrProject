<?php

/**
 * Bitrix vars
 * @global CUser $USER
 * @global CMain $APPLICATION
 */


use Bitrix\Main\Localization\Loc;


if($APPLICATION->GetGroupRight("xpage.settings")>"D")
{

    return [
        "parent_menu" => "global_menu_settings",
        "sort" => 1,
        "section" => 'xpagesettings',
        "text" => 'Xpage',
        "title" => 'Xpage module',
        "icon" => "xpage_menu_icon",
        'items_id' => 'menu_xpage_settings',
        'items' => [[
            'text' => Loc::getMessage("SETTINGS_MENU_MODULE_NAME"),
            'title' => Loc::getMessage("SETTINGS_MENU_MODULE_NAME"),
            "url" => "xpage_settings.php",
        ]
        ]
    ];

} else {
    return false;
}
