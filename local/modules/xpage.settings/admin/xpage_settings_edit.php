<?php


require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\Fields\SettingBool;
use Xpage\Settings\Models\Fields\SettingFile;
use Xpage\Settings\Models\Fields\SettingFloat;
use Xpage\Settings\Models\Fields\SettingInt;
use Xpage\Settings\Models\Fields\SettingString;
use Xpage\Settings\Models\Fields\SettingTypeInterface;

$module_id = "xpage.settings";

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/modules/'. $module_id . "/include.php");
Loc::loadMessages(__FILE__);

global $APPLICATION;
/**@var string $REQUEST_METHOD */
/**@var string $Update */
/**@var string $Apply */
/**@var string $mid */

$APPLICATION->SetTitle(Loc::getMessage("SETTINGS_MODULE_TITLE"));

Loader::IncludeModule($module_id);

if ($REQUEST_METHOD === 'POST')
{
    if ($_REQUEST['setting_active_tab'] === 'settings')
    {
        $settingCollection = Setting::getAll();
        foreach ($settingCollection as $setting)
        {
            /**@var Setting $setting */
            $inDeleteQueue = $setting->get('code') . '_del';
            $fieldTypeCode = $setting->getField()->getCode();
            if (isset($_REQUEST[$inDeleteQueue]) && $_REQUEST[$inDeleteQueue] === 'Y')
            {
                if ($fieldTypeCode === 'file' && $setting->get('value') > 0)
                {
                    CFile::Delete($setting->getValue());
                }
                $setting->delete();
            }
            else if ($fieldTypeCode === 'bool')
            {
                if (!isset($_REQUEST[$setting->getCode()]))
                {
                    $setting->setValue(null);
                }
                else
                {
                    $setting->setValue(true);
                }
            }
            else if ($fieldTypeCode === 'file')
            {
                $file = $_FILES[$setting->getCode()];
                if ((int)$file['size'] > 0 && (int)$file['error'] === 0)
                {
                    $fileId = CFile::SaveFile($file, '/modules/xpage.settings');
                    if (!empty($fileId))
                    {
                        if (!empty($oldFileId = $setting->getValue()))
                        {
                            CFile::Delete($oldFileId);
                        }
                        $setting->setValue($fileId);
                    }
                    else
                    {
                        $deleteFileKey = 'delete_file_' . $setting->getCode();
                        if ((int)$file['size'] === 0 && isset($_REQUEST[$deleteFileKey]) && $_REQUEST[$deleteFileKey] === 'Y')
                        {
                            CFile::Delete($setting->getValue());
                            $setting->setValue(null);
                        }
                    }
                }
            }
            else if ($fieldTypeCode === 'integer')
            {
                $newNumber = $_REQUEST[$setting->getCode()];
                if (isset($newNumber) && strlen($newNumber) > 0)
                {
                    $setting->setValue((int)$newNumber);
                }
                else
                {
                    $setting->setValue(null);
                }
            }
            else if ($fieldTypeCode === 'float')
            {
                $newNumber = $_REQUEST[$setting->getCode()];
                if (isset($newNumber)&& strlen($newNumber) > 0)
                {
                    $setting->setValue((float)$newNumber);
                }
                else
                {
                    $setting->setValue(null);
                }
            }
            else
            {
                $newValue = $_REQUEST[$setting->get('code')];
                if (isset($newValue))
                {
                    $setting->setValue($newValue);
                }
            }
        }
    }
    else if($_REQUEST['setting_active_tab'] === 'settings_delete_prop' && isset($_REQUEST['delete_prop']))
    {
        $setting = Setting::getByCode($_REQUEST['delete_prop']);
        if(!is_null($setting))
        {
            $setting->delete();
        }
    }
    else
    {
        $value = null;
        switch ($_REQUEST['setting_active_tab'])
        {
            case 'settings_add_string':
                $value = (string)$_REQUEST['add_string_value'];
                SettingString::add([
                    'code'  => $_REQUEST['add_string_code'],
                    'value' => $value,
                    'name'  => $_REQUEST['add_string_name'],
                ]);
                break;
            case 'settings_add_bool':
                $value = (bool)$_REQUEST['add_bool_value'];
                SettingBool::add([
                    'code'  => $_REQUEST['add_bool_code'],
                    'value' => $value,
                    'name'  => $_REQUEST['add_bool_name'],
                ]);
                break;
            case 'settings_add_integer':
                $value = (int)$_REQUEST['add_integer_value'];
                SettingInt::add([
                    'code'  => $_REQUEST['add_integer_code'],
                    'value' => $value,
                    'name'  => $_REQUEST['add_integer_name'],
                ]);
                break;
            case 'settings_add_float':
                $value = (float)$_REQUEST['add_float_value'];
                SettingFloat::add([
                    'code'  => $_REQUEST['add_float_code'],
                    'value' => $value,
                    'name'  => $_REQUEST['add_float_name'],
                ]);
                break;
            case 'settings_add_file':
                $value = $_FILES['add_file_value'];
                SettingFile::add([
                    'code'  => $_REQUEST['add_file_code'],
                    'value' => $value,
                    'name'  => $_REQUEST['add_file_name'],
                ]);
                break;
        }
    }
}

$settingCollection = Setting::getAll();
$formBuilder = new CAdminForm("setting", [
    [
        "DIV"   => "settings",
        "TAB"   => "Свойства",
        "TITLE" => "Свойства",
    ],
    [
        "DIV"   => "settings_delete_prop",
        "TAB"   => "Удалить свойство",
        "TITLE" => "Удалить",
    ],
    [
        "DIV"   => "settings_add_string",
        "TAB"   => "Добавить строку",
        "TITLE" => "Добавить строку",
    ],
    [
        "DIV"   => "settings_add_bool",
        "TAB"   => "Добавить булевое",
        "TITLE" => "Добавить булевое",
    ],
    [
        "DIV"   => "settings_add_integer",
        "TAB"   => "Добавить целое число",
        "TITLE" => "Добавить целое число",
    ],
    [
        "DIV"   => "settings_add_float",
        "TAB"   => "Добавить число",
        "TITLE" => "Добавить число",
    ],
    [
        "DIV"   => "settings_add_file",
        "TAB"   => "Добавить файл",
        "TITLE" => "Добавить файл",
    ],
]);
$formBuilder->Begin([
    'FORM_ACTION' => $APPLICATION->GetCurPage() . "?lang=ru&mid=$module_id",
]);
$formBuilder->BeginNextFormTab();
if (count($settingCollection) > 0)
{
    /**@var SettingTypeInterface $settingField */
    foreach ($settingCollection as $settingField)
    {
        $settingField->draw($formBuilder);
    }
}

$deletePropsParams = [];
foreach ($settingCollection as $settingField)
{
    $deletePropsParams[$settingField->getCode()] = $settingField->getName() . " ({$settingField->getCode()})";
}
$formBuilder->BeginNextFormTab();
if(count($deletePropsParams) > 0)
{
    $formBuilder->AddDropDownField(
        'delete_prop',
        'Удалить свойство:',
        false,
        $deletePropsParams,
    );
}



$formBuilder->BeginNextFormTab();
$formBuilder->AddEditField('add_string_code', 'Уникальный идентификатор:', true);
$formBuilder->AddEditField('add_string_name', 'Название:', true);
$formBuilder->AddEditField('add_string_value', 'Значение:', true);

$formBuilder->BeginNextFormTab();
$formBuilder->AddEditField('add_bool_code', 'Уникальный идентификатор:', true);
$formBuilder->AddEditField('add_bool_name', 'Название:', true);
$formBuilder->AddCheckBoxField('add_bool_value', 'Да:', true, '1', true);

$formBuilder->BeginNextFormTab();
$formBuilder->AddEditField('add_integer_code', 'Уникальный идентификатор:', true);
$formBuilder->AddEditField('add_integer_name', 'Название:', true);
$formBuilder->AddEditField('add_integer_value', 'Значение:', true);

$formBuilder->BeginNextFormTab();
$formBuilder->AddEditField('add_float_code', 'Уникальный идентификатор:', true);
$formBuilder->AddEditField('add_float_name', 'Название:', true);
$formBuilder->AddEditField('add_float_value', 'Значение:', true);

$formBuilder->BeginNextFormTab();
$formBuilder->AddEditField('add_file_code', 'Уникальный идентификатор:', 'Y', [], null);
$formBuilder->AddEditField('add_file_name', 'Название:', 'Y', [], null);
$formBuilder->AddFileField('add_file_value', 'Файл:', null, [], true);

$formBuilder->Buttons([
    'btnSave'  => true,
    'btnApply' => false,
]);
$formBuilder->Show();

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
