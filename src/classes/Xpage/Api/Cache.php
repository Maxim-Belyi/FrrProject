<?php

namespace Xpage\Api;

class Cache
{

    public const CACHE_DIR = '/bitrix/cache/api_no_core_included';
    /**
     * @var string
     */
    private $endPoint;
    /**
     * @var int
     */
    private $ttl;
    /**
     * @var string
     */
    private $key = '';
    /**
     * @var array
     */
    private $params;
    /**
     * @var string
     */
    private $paramsHash = 'default';

    public function getDirPath(bool $createIfNotExists = false): string
    {
        if (!isset($this->endPoint))
        {
            throw new \Exception('Кеширование невозможно без endpont');
        }
        $cacheParts = explode('/', trim($this->endPoint, '/'));
        $cacheParts = array_map('urlencode', $cacheParts);

        /*для предсказуемой глубины кэша в 2 уровня*/
        $firstCacheDir = join('_', [ array_shift($cacheParts), array_shift($cacheParts) ]) ?: 'default';
        $secondLevelCacheDir = join('_', $cacheParts) ?: 'default';

        $cachedDirPath = $_SERVER[ 'DOCUMENT_ROOT' ] . join('/', [
                self::CACHE_DIR,
                $firstCacheDir,
                $secondLevelCacheDir,
            ]);
        if ($createIfNotExists && !file_exists($cachedDirPath))
        {
            mkdir($cachedDirPath, 0755, true);
        }
        return $cachedDirPath;
    }

    private static function recursiveDirDelete($dir): bool
    {
        if (!file_exists($dir))
        {
            return false;
        }
        $files = array_diff(scandir($dir), [ '.', '..' ]);
        foreach ($files as $file)
        {
            (is_dir("$dir/$file")) ? self::recursiveDirDelete("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public static function clearPathCache(string $endPointPath): void
    {
        $Cache = new self();
        $Cache->setEndPoint($endPointPath);
        try
        {
            $path = $Cache->getDirPath();
            self::recursiveDirDelete($path);

        } catch (\Throwable $e)
        {
            \Xpage\Tools::log([
                'path' => $endPointPath,
                'dir'  => $path,
                'e'    => $e->getMessage(),
            ], __FUNCTION__);
        }
    }

    public static function clearAllCache(int $limitAge = 60 * 60 * 24 * 3): string
    {
        try
        {
            $log = [
                'filesRemoved' => 0,
                'dirRemoved'   => 0,
            ];
            $files = glob($_SERVER[ 'DOCUMENT_ROOT' ] . self::CACHE_DIR . '/*/*/*.cache');

            $dirs = [];
            foreach ($files as $filePath)
            {
                $dirs[] = pathinfo($filePath, PATHINFO_DIRNAME);
                $fileAge = time() - filemtime($filePath);


                if ($fileAge > $limitAge)
                {
                    unlink($filePath);
                    $log[ 'filesRemoved' ]++;
                }
            }
            //удаляем пустые папки
            foreach (array_unique($dirs) as $dirPath)
            {
                if (!glob($dirPath . '/*'))
                {
                    $parentDir = dirname($dirPath);
                    rmdir($dirPath);
                    $log[ 'dirRemoved' ]++;
                    if (!glob($parentDir . '/*'))
                    {
                        rmdir($parentDir);
                        $log[ 'dirRemoved' ]++;
                    }
                }
            }

        } catch (\Throwable $e)
        {
            if (defined(B_PROLOG_INCLUDED))
            {
                \Xpage\Tools::log([
                    'log' => $log,
                    'e'   => (string)$e,
                ], __FUNCTION__);
            }
        }
        return '\Xpage\Api\Cache::clearAllCache(259200);';
    }

    public function setEndPoint(string $endPoint): self
    {
        $this->endPoint = $endPoint;
        return $this;
    }

    public function setTtl(int $ttl): self
    {
        $this->ttl = $ttl;
        return $this;
    }

    public function setKey($key = null): self
    {
        $this->key = (string)$key;
        return $this;
    }

    public function setResult($result):void
    {


        $path = $this->getFilePath(true);
        file_put_contents($path, Handler::jsonEncode($result));

        $this->setEtag();
    }

    public function isValid(): bool
    {
        $pathToCache = $this->getFilePath();
        if (!file_exists($pathToCache))
        {
            return false;
        }
        if (!isset($this->ttl))
        {
            return true;
        }
        $notExpired =  (time() - filemtime($pathToCache)) < $this->ttl;

        //если он устарел, то сразу удаляем
        if(!$notExpired)
        {
            unlink($pathToCache);
        }
        return $notExpired;


    }

    //вернуть данные из кеша, если они есть
    public function getData()
    {

        if(!$this->isValid())
        {
            return null;
        }

        //вернем браузерный кеш
        $this->checkEtag();
        return json_decode(file_get_contents($this->getFilePath()),1);
    }

    //от чего может зависеть результат запроса
    public function setDynamicParams(array $params)
    {
        $this->paramsHash = md5(serialize($params));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFilePath(bool $createIfNotExists = false): string
    {
        $cacheDir = $this->getDirPath($createIfNotExists);
        $fileName = md5($this->paramsHash . '_' . $this->key) . '.cache';
        return  $cacheDir . '/' . $fileName;
    }

    private function setEtag()
    {
        $filemtime = filemtime($this->getFilePath());
        $hash = md5($filemtime);
        $eTag = "\"$hash\"";
        header("Etag: $eTag", 1);
        header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T", $filemtime));
    }

    private function checkEtag():void
    {
        $hash = md5(filemtime($this->getFilePath()));
        $eTag = "\"$hash\"";

        header("Etag: $eTag", 1);

        if ($_SERVER[ 'HTTP_IF_NONE_MATCH' ] == 'W/' . $eTag || $_SERVER[ 'HTTP_IF_NONE_MATCH' ] == $eTag)
        {
            http_response_code(304);
            die();
        }

    }
}