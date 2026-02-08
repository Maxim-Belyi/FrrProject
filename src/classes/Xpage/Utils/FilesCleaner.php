<?php
/** пример*/
//  $FC = new \Xpage\Tools\FilesCleaner('/upload/report/v2/*/*/*');
//    $FC->setTimestamp(strtotime(' - 3 days')); файлы старше трех дней
//    $FC->stopAfter(strtotime('+ 10 seconds')); не работать дольше 10 секунд
//    $FC->deleteFiles(); процесс удаления
//echo $FC->getDeletedFilesCount(); сколько удалили
//echo \Xpage\Tools\FilesCleaner::clearEmptyDirs('/upload/report/v2/*/*'); чистим пустые папки
namespace Xpage\Utils;

class FilesCleaner
{

    private $limitTimestamp ;
    private $extensions = [];
    /**
     * @var int
     */
    private $deleteCountLimit;
    /**
     * @var \GlobIterator
     */
    private $Iterator;
    /**
     * @var int
     */
    private $whenToStop;
    private $filesDeleted = 0;

    public function __construct(string $mask)
    {
        if (mb_stripos($mask, $_SERVER[ 'DOCUMENT_ROOT' ]) === false)
        {
            $mask = implode('/', [ $_SERVER[ 'DOCUMENT_ROOT' ], ltrim($mask, '/') ]);
        }

        $this->Iterator = new \GlobIterator($mask);

    }

    //удалить пустые директории по маске
    public static function clearEmptyDirs(string $dirCleanMask): int
    {
        $counter = 0;
        try
        {
            if (mb_stripos($dirCleanMask, $_SERVER[ 'DOCUMENT_ROOT' ]) === false)
            {
                $dirCleanMask = implode('/', [ $_SERVER[ 'DOCUMENT_ROOT' ], ltrim($dirCleanMask, '/') ]);
            }
            $Iterator = new \GlobIterator($dirCleanMask, \FilesystemIterator::SKIP_DOTS);
            foreach ($Iterator as $FileData)
            {
                if (!$FileData->isDir())
                {
                    continue;
                }
                $Dir = $FileData;

                $FS = new \FilesystemIterator($Dir->getPathname());
                if (!$FS->valid())
                {
                    rmdir($Dir->getPathname());
                    $counter++;
                }

            }
        } catch (\Throwable $e)
        {
            return -1;
        }
        return $counter;

    }

    //установить временную метру, до которой файл считается устаревшим
    public function setTimestamp(int $timestamp): self
    {
        if ($timestamp >= time())
        {
            throw new \Exception('Временная метка должна быть из прошлого');
        }
        $this->limitTimestamp = $timestamp;
        return $this;
    }

    //какие расширения удалять
    public function addExtensionFilter(string $extension): self
    {
        $this->extensions[ $extension ] = 1;
        return $this;
    }

    //сколько удалять максимум
    public function setLimit(int $countLimit): self
    {
        $this->deleteCountLimit = $countLimit;
        return $this;
    }

    //когда перестать удалять
    public function stopAfter(int $timestamp)
    {
        $this->whenToStop = $timestamp;
    }

    //удаляем файлы
    public function deleteFiles():void
    {
        foreach ($this->Iterator as $FileItem)
        {
            if (!empty($this->deleteCountLimit) && $this->filesDeleted >= $this->deleteCountLimit)
            {
                break;
            }
            if (isset($this->whenToStop) && time() > $this->whenToStop)
            {
                return;
            }
            //если файл старше допустимого лимита
            if (isset($this->limitTimestamp))
            {
                if ($FileItem->getCTime() > $this->limitTimestamp)
                {
                    break;
                }
            }
            //если есть фильтр по расширениям и наше не подходит
            if (!empty($this->extensions) && empty($this->extensions[ $FileItem->getExtension() ]))
            {
                continue;
            }
            if (unlink($FileItem->getPathname()))
            {
                $this->filesDeleted++;
            }

        }
    }

    /**
     * @return int
     */
    public function getDeletedFilesCount(): int
    {
        return $this->filesDeleted ?? 0;
    }


}