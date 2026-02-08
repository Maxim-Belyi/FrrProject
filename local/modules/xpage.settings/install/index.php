<?php
declare(strict_types=1);

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;


$pathInstall = str_replace("\\", "/", __FILE__);
$pathInstall = substr($pathInstall, 0, strlen($pathInstall) - strlen("/index.php"));
Loc::loadMessages(__FILE__);
include($pathInstall . "/version.php");
if (class_exists("xpage_settings"))
{
	return;
}

class xpage_settings extends CModule
{
	public $MODULE_ID = "xpage.settings";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_GROUP_RIGHTS = "Y";




	public function __construct()
	{
		include(__DIR__ . '/version.php');
		/**@var array $arModuleVersion */
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = Loc::getMessage("SETTINGS_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("SETTINGS_MODULE_DESCRIPTION");
	}

	public function DoInstall(): bool
	{
		global $APPLICATION, $step, $DB, $DBType;
		$FORM_RIGHT = $APPLICATION::GetGroupRight($this->MODULE_ID);
		if ($FORM_RIGHT !== "W")
		{
			return false;
		}



		$this->InstallTasks();
		RegisterModule($this->MODULE_ID);
		Loader::IncludeModule($this->MODULE_ID);
        $this->InstallFiles();
		$this->createTables();

		return true;
	}

    public function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT  . '/admin', true, true);
        CopyDirFiles(__DIR__ . '/themes', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/themes', true, true);
    }

    /**
	 * @return void
	 */
	public function createTables(): void
	{
		global $DB, $DBType;
		if ($DBType === 'mysql')
		{
			$DB->Query("create table xpage_setting_fields
			(
				id            int unsigned auto_increment
					primary key,
				name          varchar(255) not null,
				code          varchar(255) not null,
				field_handler varchar(255) not null,
				constraint xpage_setting_fields_pk
					unique (code)
			)");
			$DB->Query("create table xpage_settings
			(
				id         int unsigned auto_increment
					primary key,
				field_type int unsigned    not null,
				code       varchar(255)    not null,
				name       varchar(255)    not null,
				value      varchar(255)    null,
				sort       int default 100 not null,
				constraint xpage_settings_code_uindex
					unique (code),
				constraint common_params___fk
					foreign key (field_type) references xpage_setting_fields (id)
			)");

			$DB->Query('insert into xpage_setting_fields (name, code, field_handler)
						values  ("Булевое", "bool", "\\\Xpage\\\Settings\\\Models\\\Fields\\\SettingBool"),
								("Строка", "string", "\\\Xpage\\\Settings\\\Models\\\Fields\\\SettingString"),
								("Файл", "file", "\\\Xpage\\\Settings\\\Models\\\Fields\\\SettingFile"),
								("Целое число", "integer", "\\\Xpage\\\Settings\\\Models\\\Fields\\\SettingInt"),
								("Число", "float", "\\\Xpage\\\Settings\\\Models\\\Fields\\\SettingFloat");'
			);
		}
		else
		{
			throw new RuntimeException($DBType . ' БД не поддерживается');
		}
	}

	/**
	 * @return void
	 */
	public function DoUninstall(): void
	{
		global $APPLICATION, $step;
		$FORM_RIGHT = $APPLICATION::GetGroupRight($this->MODULE_ID);
		if ($FORM_RIGHT !== "W")
		{
			return;
		}

		$this->dropTables();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
	}

	private function dropTables(): void
	{
		global $DB, $DBType;
		if ($DBType === 'mysql')
		{
			$DB->Query("drop table if exists xpage_settings;");
			$DB->Query("drop table if exists xpage_setting_fields;");
		}
		else
		{
			throw new RuntimeException($DBType . ' БД не поддерживается');
		}
	}

	/**
	 * @return array
	 */
	public function GetModuleRightList(): array
	{
		return [
			"reference_id" => ["D", "R", "W"],
			"reference"    => [
				Loc::getMessage("FORM_DENIED"),
				Loc::getMessage("FORM_OPENED"),
				Loc::getMessage("FORM_FULL")],
		];
	}

    public function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/admin');
        DeleteDirFiles(__DIR__ . '/themes', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/themes');
    }

}