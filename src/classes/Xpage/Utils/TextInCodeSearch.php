<?php

namespace Xpage\Utils;
/*
 * Пример
 *  $S = new \Xpage\Utils\TextInCodeSearch;
    $S->setText('искомый текст в файлах');
    $S->setMask(['php','js']);
    $S->setDirectory($_SERVER['DOCUMENT_ROOT']);
    $S->search();
 *
 *
 *
 * */
class TextInCodeSearch
{
    /**
     * @var string
     */
    private $text;
    private $directory;
    private $checked = 0;
    private $skipped = 0;
    /**
     * @var array
     */
    private $extensions;

    public function __construct()
    {
        $this->setDirectory($_SERVER['DOCUMENT_ROOT']);
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function setDirectory(string $directory)
    {
        $path = rtrim($directory,DIRECTORY_SEPARATOR);
        if(!is_dir($path))
        {
            throw new \Exception('директории не существует');
        }
        $this->directory = $path;
    }

    public function search()
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directory,\FilesystemIterator::SKIP_DOTS));

        /** @var \SplFileInfo $file */
        foreach ($rii as $file)
        {

            if ($file->isDir())
            {
                continue;
            }
            $this->checked++;
            if(!empty($this->extensions))
            {
                if(!in_array($file->getExtension(),$this->extensions))
                {
                    continue;
                }
            }
            $this->onEachFile($file->getPathname());
        }


    }

    //срабатывает на каждом файле
    private function onEachFile(string $path)
    {
        if(!$this->hasTextInContent($path))
        {
            return;
        }

        echo $path.'<br>';
    }

    public function setMask(array $extensions)
    {
        $this->extensions = $extensions;
    }

    private function hasTextInContent(string $path):bool
    {
        $content = file_get_contents($path);
        return mb_stripos($content,$this->text)!==false;
    }
}