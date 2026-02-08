<?php

namespace Xpage\Utils;

use Xpage\Tools;
use Bitrix\Main\IO\Path;

class VueFiles
{

    private string $fullPathToDist;
    private bool $useHash;
    public function __construct(string $relativePath,bool $useHash = false)
    {
        $this->fullPathToDist = Path::convertSiteRelativeToAbsolute($relativePath);
        if(!is_dir($this->fullPathToDist))
        {
            //todo уведомить об ошибке
        }
        $this->useHash = $useHash;
    }

    private function getDistDir(): string
    {
        return $this->fullPathToDist;
    }

    private function getCssFiles(): array
    {

        $files = [];
        if($this->useHash)
        {

            $appCssFiles = glob($this->getDistDir() . '/css/app.*css');
            $vendorCssFiles = glob($this->getDistDir() . '/css/chunk-vendors.*css');
        }
        else{
            $appCssFiles = glob($this->getDistDir() . '/css/app.css');
            $vendorCssFiles = glob($this->getDistDir() . '/css/chunk-vendors.css');
        }

        if ($appCssFiles)
        {
            $files[] = $appCssFiles[ 0 ];
        }
        if ($vendorCssFiles)
        {
            $files[] = $vendorCssFiles[ 0 ];
        }
        foreach ($files as &$path)
        {
            $path = str_replace($_SERVER[ 'DOCUMENT_ROOT' ], '', $path);
        }
        return $files;
    }
    private function getJsFiles(): array
    {
        $scriptFiles = [];
        if($this->useHash)
        {

            $jsFiles = glob($this->getDistDir() . '/js/app.*js');

            usort($jsFiles,fn($pathA, $pathB) => filemtime($pathA) < filemtime($pathB));

            $vendorFiles = glob($this->getDistDir() . '/js/chunk-vendors.*js');
            usort($vendorFiles,fn($pathA, $pathB) => filemtime($pathA) < filemtime($pathB));
        }
        else
        {
            $jsFiles = glob($this->getDistDir() . '/js/app.js');
            $vendorFiles = glob($this->getDistDir() . '/js/chunk-vendors.js');
        }

        if ($jsFiles)
        {
            $scriptFiles[] = $jsFiles[ 0 ];
        }
        if ($vendorFiles)
        {
            $scriptFiles[] = $vendorFiles[ 0 ];
        }
        foreach ($scriptFiles as &$filePath)
        {
            //переделаем путь в относительный
            $filePath = str_replace($_SERVER[ 'DOCUMENT_ROOT' ], '', $filePath);
        }
        return $scriptFiles;
    }

    public function registerAssets():void
    {
        try
        {
            $assets = \Bitrix\Main\Page\Asset::getInstance();
            foreach (self::getCssFiles() as $path)
            {

                $assets->addCss($path);
            }
            foreach (self::getJsFiles() as $path)
            {
                $assets->addJs($path);
            }
        }
        catch (\Throwable $e)
        {
            Tools::log($e->getMessage(),__FUNCTION__,'errors');
        }
    }
}