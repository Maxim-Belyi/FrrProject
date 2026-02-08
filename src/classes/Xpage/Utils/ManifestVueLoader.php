<?php
namespace Xpage\Utils;

final class ManifestVueLoader
{
    const DIST_DIR_NAME = '';
    const FRONT_LAYOUT_PATH = '/local/frontend-build';
    const MANIFEST_NAME = '.vite/manifest.json';
    private string               $fullPathToApp;
    private \Bitrix\Main\IO\File $manifestFile;

    public function __construct(private readonly string $relativePathToApp,private readonly string $documentRoot)
    {
        $this->fullPathToApp = \Bitrix\Main\IO\Path::combine(\Bitrix\Main\Application::getDocumentRoot(), $this->relativePathToApp);

    }

    public static function loadLayout(): void
    {
        $loader = new ManifestVueLoader('local/frontend-build',$_SERVER['DOCUMENT_ROOT']);
        if($loader->manifestExists())
        {
            $loader->loadJs();
            $loader->loadCss();
        }
        else
        {
            $loader->findError();
        }
    }

    public function findError()
    {

        $applicationDir = $this->getAppDir();
        if ($applicationDir->isExists() === false)
        {
            throw new \Bitrix\Main\SystemException("Папка приложения не найдена");
        }

        $nodeModulesDir = $this->getNodeModulesDir();
        if ($nodeModulesDir->isExists() === false)
        {
            throw new \Bitrix\Main\SystemException("Папка node_modules не найдена. Попробуйте npm install");
        }

        $distDir = $this->getDistDir();
        if ($distDir->isExists() === false)
        {
            throw new \Bitrix\Main\SystemException("Папка dist не найдена. Попробуйте npm run build");
        }

        $manifestFile = $this->getManifestFile();
        if ($manifestFile->isExists() === false)
        {
            throw new \Bitrix\Main\SystemException("Файл манифеста не найден. Попробуйте npm run build");
        }

        $manifestData = $this->getManifestData();
        if (empty($manifestData))
        {
            throw new \Bitrix\Main\SystemException("Файл манифеста пустой. Попробуйте npm run build");
        }
    }

    private function getAppDir(): \Bitrix\Main\IO\Directory
    {
        return new \Bitrix\Main\IO\Directory($this->fullPathToApp);
    }

    private function getDistDir(): \Bitrix\Main\IO\Directory
    {
        $distPath = \Bitrix\Main\IO\Path::combine($this->fullPathToApp, self::DIST_DIR_NAME);
        return new \Bitrix\Main\IO\Directory($distPath);
    }

    private function getNodeModulesDir(): \Bitrix\Main\IO\Directory
    {
        $nodeModulesPath = \Bitrix\Main\IO\Path::combine($this->fullPathToApp, 'node_modules');
        return new \Bitrix\Main\IO\Directory($nodeModulesPath);
    }

    private function getManifestFile(): \Bitrix\Main\IO\File
    {
        if (empty($this->manifestFile))
        {
            $manifestPath = \Bitrix\Main\IO\Path::combine($this->fullPathToApp, self::DIST_DIR_NAME, self::MANIFEST_NAME);
            $this->manifestFile = new \Bitrix\Main\IO\File($manifestPath);
        }

        return $this->manifestFile;
    }

    private function manifestExists(): bool
    {
        $manifestFile = $this->getManifestFile();
        return $manifestFile->isExists();
    }

	/*
    private function loadJs(): void
    {
        foreach ($this->getJsFiles() as $jsFile)
        {
            \Bitrix\Main\Page\Asset::getInstance()->addJs($jsFile);
        }
    }
	*/

	private function loadJs(): void
    {
        foreach ($this->getJsFiles() as $jsFile)
        {
            $string = sprintf('<script type="module" crossorigin src="%s"></script>',$jsFile);
            \Bitrix\Main\Page\Asset::getInstance()->addString($string,true,\Bitrix\Main\Page\AssetLocation::BODY_END);
        }
    }
	
    private function loadCss():void
    {
        foreach ($this->getCssFiles() as $cssFile)
        {
            \Bitrix\Main\Page\Asset::getInstance()->addCss($cssFile);
        }
    }

    private function getJsFiles(): array
    {
        $list = [];
        $manifestData = $this->getManifestData();
        $distScriptPath = $manifestData[ 'app/main.ts' ][ 'file' ];

        $list[] = $this->convertDistPathToSiteRelative($distScriptPath);
        return $list;
    }


    private function convertDistPathToSiteRelative(string $distRelativePath):string
    {
        $fullFilePath = \Bitrix\Main\IO\Path::combine($this->fullPathToApp, self::DIST_DIR_NAME, $distRelativePath);
        //вычтем документ рут
        return str_replace($_SERVER[ 'DOCUMENT_ROOT' ], '', $fullFilePath);
    }

    private function getCssFiles():array
    {
        $manifestData = $this->getManifestData();
        $distCssList = $manifestData[ 'app/main.ts' ][ 'css' ];
        $list = array_map($this->convertDistPathToSiteRelative(...), $distCssList);
        return $list;

    }

    private function getManifestData(): array
    {
        $manifestFile = $this->getManifestFile();
        $manifestData = $manifestFile->getContents();
        return json_decode($manifestData, true, JSON_THROW_ON_ERROR);
    }

    public function getDebugData()
    {
        $return = [

        ];
        try
        {
            $return[ 'fullPathToApp' ] = $this->fullPathToApp;
            $return[ 'manifestFile' ] = $this->getManifestFile()->getPath();
            $return[ 'manifestExists' ] = $this->manifestExists();
            $return[ 'manifestDataNotEmpty' ] = !empty($this->getManifestData());
            $return[ 'distDir' ] = $this->getDistDir()->getPath();
            $return[ 'nodeModulesDir' ] = $this->getNodeModulesDir()->getPath();
            $return[ 'appDir' ] = $this->getAppDir()->getPath();
            $return[ 'jsFiles' ] = $this->getJsFiles();
            $return[ 'cssFiles' ] = $this->getCssFiles();

        }
        catch (\Exception $e)
        {
            $return[ 'error' ] = $e->getMessage();
        }
        return $return;

    }
    public function getSpritemapPath(): string
    {
        $manifestData = $this->getManifestData();
        return $manifestData['spritemap.svg']["file"] ?? "";
    }

    public function getSprite(string $icon = ''): string
    {
        if ($icon)
        {
            $icon = '#' . $icon;
        }
        return self::FRONT_LAYOUT_PATH . "/" . $this->getSpritemapPath() . $icon;
    }
}
?>