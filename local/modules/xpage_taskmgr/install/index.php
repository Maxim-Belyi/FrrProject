<?php

use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);
if (class_exists("xpage_taskmgr"))
{
    return;
}

class xpage_taskmgr extends CModule
{
    public $MODULE_ID           = "xpage_taskmgr";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $MODULE_GROUP_RIGHTS = "N";

    function __construct()
    {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion[ "VERSION" ];
        $this->MODULE_VERSION_DATE = $arModuleVersion[ "VERSION_DATE" ];
        $this->MODULE_NAME = Loc::getMessage('MODULE_XPAGE_MODULE_NAME');//GetMessage("HLBLOCK_MODULE_NAME");
        $this->MODULE_DESCRIPTION = 'xpage taskmgr';//GetMessage("HLBLOCK_MODULE_DESCRIPTION");
    }

    function GetModuleTasks()
    {
        return array();
    }

    function InstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->InstallTasks();
        RegisterModule("xpage_taskmgr");
        CModule::IncludeModule("xpage_taskmgr");
        global $DB, $DBType;
        switch ($DBType)
        {
            case 'mysql':
                $DB->Query("CREATE TABLE IF NOT EXISTS xpage_taskmgr_tasks
						(
							ID INT(18) not null auto_increment,
							ACTIVE char(1) not null DEFAULT 'Y',
							PRIORITY INT(18),
							NAME VARCHAR(250) not null,
							TIME_START TIME,
							COMMAND VARCHAR(250),
							ENABLE_SMS char(1) not null DEFAULT 'Y',
							ENABLE_MAIL char(1) not null DEFAULT 'Y',
							SMS_MESSAGE_OK TEXT,
							SMS_MESSAGE_ERROR TEXT,
							EMAIL_MESSAGE_OK TEXT,
							EMAIL_MESSAGE_ERROR TEXT,
							SMS_PHONES TEXT,
							EMAIL_ADDESSES TEXT,
							TASK_GROUP INT(18) DEFAULT 1,
							TASK_INTERVAL INT(18),
							NOTIFICATIONS_COUNT INT(18),
							UNSUCCESSFUL_RUN_COUNT INT(18) DEFAULT 0,
							RUN_FORCED char(1) not null DEFAULT 'N',
							AUTORUN char(1) not null DEFAULT 'N',
							primary key (ID)
						);");
                $DB->Query("CREATE TABLE IF NOT EXISTS xpage_taskmgr_log
						(
							ID INT(18) not null auto_increment,
							TASK_ID INT(18),
							STATUS VARCHAR(250),
							DATETIME_START DATETIME,
							DATETIME_END DATETIME,
							RUN_TYPE VARCHAR(250) not null DEFAULT 'shedule',
							primary key (ID)
						);");
                $DB->Query("CREATE TABLE IF NOT EXISTS xpage_taskmgr_log_messages
						(
							ID INT(18) not null auto_increment,
							LOG_ID INT(18),
							MESSAGE LONGTEXT,
							DATETIME DATETIME,
							primary key (ID)
						);");
                $DB->Query('create index taskId on xpage_taskmgr_log(TASK_ID)');
                $DB->Query('create index taskIdStatus on xpage_taskmgr_log(TASK_ID,STATUS)');
                $DB->Query('create index logId on xpage_taskmgr_log_messages(LOG_ID)');
            break;

            default:
                # code...
            break;
        }
        return true;
    }

    function UnInstallDB($arParams = array())
    {
        UnRegisterModule("xpage_taskmgr");
        global $DB, $DBType;
        switch ($DBType)
        {
            case 'mysql':
                $DB->Query("DROP TABLE IF EXISTS xpage_taskmgr_tasks;");
                $DB->Query("DROP TABLE IF EXISTS xpage_taskmgr_log;");
                $DB->Query("DROP TABLE IF EXISTS xpage_taskmgr_log_messages;");
            break;

            default:
                # code...
            break;
        }
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles($arParams = array()):bool
    {
        //компоненты
        $targetComponentPath = $this->getComponentsTargetPath();
        $sourceComponentPath = $this->getComponentSourcePath();
        if (!\Bitrix\Main\IO\Directory::isDirectoryExists($targetComponentPath))
        {
           \Bitrix\Main\IO\Directory::createDirectory($targetComponentPath);
        }
        CopyDirFiles($sourceComponentPath, $targetComponentPath, true, true);
        //точка хода
        $targetEntryPointPath = $this->getEntryTargetPath();
        $sourceEntryPointPath = $this->getEntrySourcePath();
        if(!\Bitrix\Main\IO\File::isFileExists($targetEntryPointPath))
        {
            \Bitrix\Main\IO\Directory::createDirectory($targetEntryPointPath);
        }
        CopyDirFiles($sourceEntryPointPath, $targetEntryPointPath, true, true);
        return true;
    }

    function UnInstallFiles():bool
    {
        $componentTargetPath = $this->getComponentsTargetPath();
        if (\Bitrix\Main\IO\Directory::isDirectoryExists($componentTargetPath))
        {
            $directory = new \Bitrix\Main\IO\Directory($componentTargetPath);
            $directory->delete();
        }
        //точка хода
        $targetEntryPointPath = $this->getEntryTargetPath();
        if (\Bitrix\Main\IO\Directory::isDirectoryExists($targetEntryPointPath))
        {
            $directory = new \Bitrix\Main\IO\Directory($targetEntryPointPath);
            $directory->delete();
        }
        return true;
    }

    function DoInstall()
    {
        global $USER;

        if ($USER->IsAdmin())
        {
            if ($this->InstallDB())
            {
                $this->InstallFiles();
            }
        }
    }

    function DoUninstall()
    {
        global $USER;
        if ($USER->IsAdmin())
        {
            $this->UnInstallDB();
            $this->UnInstallFiles();
        }
    }

    private function getComponentSourcePath(): string
    {
        return \Bitrix\Main\IO\Path::convertRelativeToAbsolute('local/modules/xpage_taskmgr/install/components/taskmgr');
    }


    private function getComponentsTargetPath(): ?string
    {
        return \Bitrix\Main\IO\Path::convertSiteRelativeToAbsolute('local/components/xpage/taskmgr');
    }
    private function getEntryTargetPath(): ?string
    {
        return \Bitrix\Main\IO\Path::convertSiteRelativeToAbsolute('taskmgr');
    }
    private function getEntrySourcePath(): ?string
    {
        return \Bitrix\Main\IO\Path::convertSiteRelativeToAbsolute('local/modules/xpage_taskmgr/install/entryPoint/taskmgr');
    }
}
