<?php namespace Xpage\Taskmgr;

use \Bitrix\Main\Type\DateTime;

class ImportXml
{
    protected $params = [
        'section_action'     => 'A',
        'element_action'     => 'A',
        "translit_params"    => [
            "max_len"               => 255,
            "change_case"           => 'L',
            "replace_space"         => '-',
            "replace_other"         => '-',
            "delete_repeat_replace" => true,
        ],
        'translit_on_add'    => [],
        'translit_on_update' => false
    ];

    protected $name;
    protected $import = ['ID' => 1];
    protected $documentRoot = __DIR__;
    protected $fileName;
    protected $iblockType = 'kb';
    protected $siteID = 's1';
    public    $taskMgr;
    public   $tableName;

    public function setTaskMgr(&$taskMgr) {
      $this->taskMgr = $taskMgr;
    }

    /**
     * @return string
     */
    public function getDocumentRoot() {
        return $this->documentRoot;
    }

    /**
     * @param string $documentRoot
     */
    public function setDocumentRoot($documentRoot) {
        $this->documentRoot = $documentRoot;
    }

    /**
     * @return string
     */
    public function getIblockType() {
        return $this->iblockType;
    }

    /**
     * @param string $iblockType
     */
    public function setIblockType($iblockType) {
        $this->iblockType = $iblockType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSiteID() {
        return $this->siteID;
    }

    /**
     * @param string $siteID
     */
    public function setSiteID($siteID) {
        $this->siteID = $siteID;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileName() {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName) {
        $this->fileName = $fileName;

        return $this;
    }

    public function __construct($task = null, $params = []) {
        if(is_numeric($task) && \CModule::IncludeModule('xpage_taskmgr')) {
            $this->import = \Xpage\Taskmgr\TaskTable::getRowById($task);
            if($this->import) $this->name = $this->import['NAME'];
        } else {
            $this->name = $task;
        }
        if($params['translit_on_update']) $params['translit_on_update'] = $this->params['translit_params'];
        if($params['translit_on_add']) $params['translit_on_add'] = $this->params['translit_params'];
        $this->params = array_merge($this->params, $params);

    }

    protected function unpackArchive($fileName, $dirName) {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;
        include_once($this->documentRoot . "/bitrix/modules/main/classes/general/tar_gz.php");
        $obArchiver = new \CArchiver($fileName);
        if(!$obArchiver->ExtractFiles($dirName)) {
            $strError = "";
            if(is_object($APPLICATION)) {
                $arErrors = $obArchiver->GetErrors();
                if(count($arErrors)) {
                    foreach($arErrors as $error) {
                        $strError .= $error[1] . "<br>";
                    }
                }
            }
            if($strError != "") {
                throw new \ErrorException($strError);
            } else {
                throw new \ErrorException(GetMessage("IBLOCK_XML2_FILE_ERROR"));
            }
        }

        return true;
    }

    public function start($use_crc = false, $preview = false, $sync = false, $return_last_error = true) {
        $em = \Bitrix\Main\EventManager::getInstance();
        if ($this->taskMgr) {
            $this->taskMgr->addLogMessage('Был запущен скрипт разбора файла ' . $this->fileName);
        }
        $ABS_FILE_NAME = false;

        if(strlen($this->fileName) > 0) {
            if(
                file_exists($this->fileName)
                && is_file($this->fileName)
                && (
                    substr($this->fileName, -4) === ".xml"
                    || substr($this->fileName, -7) === ".tar.gz"
                )
            ) {
                $ABS_FILE_NAME = $this->fileName;
            } else {
                $filename = trim(str_replace("\\", "/", trim($this->fileName)), "/");
                $this->fileName = rel2abs($this->documentRoot, "/" . $filename);
                if((strlen($this->fileName) > 1) && ($this->fileName === "/" . $filename)) {
                    $ABS_FILE_NAME = $this->documentRoot . $this->fileName;
                }
            }
        }

        if(!$ABS_FILE_NAME) {
            throw new \ErrorException("Invalid filename");
        }

        $WORK_DIR_NAME = substr($ABS_FILE_NAME, 0, strrpos($ABS_FILE_NAME, "/") + 1);

        if(substr($ABS_FILE_NAME, -7) == ".tar.gz") {
            $this->unpackArchive($ABS_FILE_NAME, $WORK_DIR_NAME);
            $IMP_FILE_NAME = substr($ABS_FILE_NAME, 0, -7) . ".xml";
        } else {
            $IMP_FILE_NAME = $ABS_FILE_NAME;
        }

        $fp = fopen($IMP_FILE_NAME, "rb");
        if(!$fp) {
            throw new \ErrorException("Cant open file");
        }

        if($sync) {
            $table_name = "b_xml_tree_sync";
        } else {
            $table_name = "b_xml_tree";
        }
        if($this->tableName)
        {
            $table_name=$this->tableName;
        }
        $NS = ["STEP" => 0];

        $obCatalog = new \CIBlockCMLImport;
        $obCatalog->translit_on_add = $this->params['translit_on_add'];
        $obCatalog->translit_on_update = $this->params['translit_on_update'];
        $obCatalog->Init($NS, $WORK_DIR_NAME, $use_crc, $preview, false, false, false, $table_name);
        if($sync) {
            if(!$obCatalog->StartSession(bitrix_sessid())) {
                throw new \ErrorException("error session");
            }

            $obCatalog->ReadXMLToDatabase($fp, $NS, 0, 1024);

            $xml_root = $obCatalog->GetSessionRoot();
            $bUpdateIBlock = false;
        } else {
            $obCatalog->DropTemporaryTables();

            if(!$obCatalog->CreateTemporaryTables()) {
                throw new \ErrorException("Table create error");
            }

            $obCatalog->ReadXMLToDatabase($fp, $NS, 0, 1024);

            if(!$obCatalog->IndexTemporaryTables()) {
                throw new \ErrorException("Index table error");
            }

            $xml_root = 1;
            $bUpdateIBlock = true;
        }

        fclose($fp);

        $result = $obCatalog->ImportMetaData($xml_root, $this->iblockType, $this->siteID, $bUpdateIBlock);
        if($result !== true) {
            throw new \ErrorException("Import metadata error" . implode("\n", $result));
        }

        $obCatalog->ImportSections();

        $sectionsCounter = $obCatalog->sectionsCounter;

        $obCatalog->DeactivateSections($this->params['section_action']);
        $obCatalog->SectionsResort();

        $obCatalog = new \CIBlockCMLImport;
        $obCatalog->translit_on_add = $this->params['translit_on_add'];
        $obCatalog->translit_on_update = $this->params['translit_on_update'];
        $obCatalog->Init($NS, $WORK_DIR_NAME, $use_crc, $preview, false, false, false, $table_name);
        if($sync) {
            if(!$obCatalog->StartSession(bitrix_sessid())) {
                throw new \ErrorException("error session");
            }
        }
        $SECTION_MAP = false;
        $PRICES_MAP = false;
        $obCatalog->ReadCatalogData($SECTION_MAP, $PRICES_MAP);
        $elementsCounter = $obCatalog->ImportElements(time(), 0);

        $obCatalog->ImportProductSets();

        $deactivateCounter = $obCatalog->DeactivateElement($this->params['element_action'], time(), 0);
        if($sync) {
            $obCatalog->EndSession();
        }

        if($return_last_error) {
            if(strlen($obCatalog->LAST_ERROR)) {
                if (strpos($obCatalog->LAST_ERROR, 'Файл не является графическим') !== false) {
                    throw new \ErrorException($obCatalog->LAST_ERROR . " стр." . __LINE__);
                }
                elseif ($this->taskMgr) {
                    $this->taskMgr->addLogMessage($obCatalog->LAST_ERROR . " стр." . __LINE__);
                }
            }
        }

        $elementsCounter['DEA'] = $deactivateCounter['DEA'];

        if ($this->taskMgr) {
            $this->taskMgr->addLogMessage("Добавлено элементов: {$elementsCounter['ADD']}<br>
                            Обновлено элементов: {$elementsCounter['UPD']}<br>
                            Деактивировано элементов: {$elementsCounter['DEA']}<br>
                            Добавлено разделов: {$sectionsCounter['ADD']}<br>
                            Обновлено разделов: {$sectionsCounter['UPD']}<br>
                            Деактивировано разделов: {$sectionsCounter['DEA']}");
        }
    }
}
