<?php
namespace Xpage\Utils;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Loader;

class Tools
{
    const IV_ENCRYPTION        = '323436789111140';
    const CIPHERING_ENCRYPTION = "AES-128-CTR";

    public static function encrypt(string $string, string $pass = 'shrtlnkenckey'): string
    {
        return openssl_encrypt($string, self::CIPHERING_ENCRYPTION, $pass, 0, self::IV_ENCRYPTION);

    }
    public static function getIblockId(string $code): int

    {
        if (!Loader::includeModule('iblock')) {
            return 0;
        }

        $result   = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['CODE' => $code],
            'cache'  => [
                'ttl'         => 3600,
                'cache_joins' => true
            ],
        ]);
        $arIblock = [];
        if ($row = $result->fetch())
        {
            $arIblock = $row;
        }

        return (int)$arIblock['ID'];
    }

    public static function decrypt(string $string, string $pass = 'shrtlnkenckey'): string
    {
        return openssl_decrypt($string, self::CIPHERING_ENCRYPTION, $pass, 0, self::IV_ENCRYPTION);

    }

    // удаление не пустой папки
    public static function rrmdir($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..'))
            {
                $full = $src . '/' . $file;
                if (is_dir($full))
                {
                    self::rrmdir($full);
                }
                else
                {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    //получить сущность хайлоадблока
    public static function getHlb($idOrName): DataManager
    {
        static $cache;
        if (isset($cache) && $cache[ $idOrName ])
        {
            return $cache[ $idOrName ];
        }
        \Bitrix\Main\Loader::includeModule("highloadblock");
        if (is_numeric($idOrName) && intval($idOrName) > 0)
        {
            $arHLBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById($idOrName)->fetch();
            $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
        }
        else
        {
            $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($idOrName);
        }

        $className = $obEntity->getDataClass();
        $cache[ $idOrName ] = new $className;
        return $cache[ $idOrName ];
    }

    //форматирование телефона
    public static function formatPhone(string $phoneRaw): string
    {
        return preg_replace([ '@\D+@ui', '@^\+?[78]@ui' ], [ '', '+7' ], $phoneRaw);
    }

    //прогрессбар для обработки в консоли
    public static function consoleProgressBar(float $done, float $total)
    {
        static $rateLog = [];
        static $rateBufferSize;

        //размер буфера замера скорости
        $rateBufferSize = 1000;

        $size = 40;
        //чтоб только из консоли
        if (!$_SERVER[ "USER" ])
        {
            return;
        }
        static $startTime;

        if ($done > $total)
        {
            return;
        }

        if (empty($startTime))
        {
            $startTime = time();
        }
        $now = time();

        $percent = (double)($done / $total);

        $bar = floor($percent * $size);

        $statusBarText = "\r[";
        $statusBarText .= str_repeat("=", $bar);
        if ($bar < $size)
        {
            $statusBarText .= ">";
            $statusBarText .= str_repeat(" ", $size - $bar);
        }
        else
        {
            $statusBarText .= "=";
        }

        $disp = number_format($percent * 100);

        $statusBarText .= "] $disp%  $done/$total";

        $rate = ($now - $startTime) / $done;


        $rateLog[] = $rate;

        //сглаживаем
        if (count($rateLog) > $rateBufferSize)
        {
            array_shift($rateLog);
        }
        $rate = round(array_sum($rateLog) / count($rateLog), 5);
        if (!$rate)
        {
            $rate = 1;
        }
        $left = $total - $done;


        $estimatedTime = round($rate * $left, 2);

        $elapsed = $now - $startTime;

        $printedDateTime = date('H:i', time() + $estimatedTime);
        if (date('d.m.Y', time() + $estimatedTime) != date('d.m.Y'))
        {
            $printedDateTime = date('d.m.Y H:i', time() + $estimatedTime);
        }
        $memory = ' Пямять ' . (round(memory_get_usage(1) / (1024 * 1024), 2)) . ' мб';
        $statusBarText .= " осталось: " . number_format($estimatedTime) . " сек.  прошло: " . number_format($elapsed) . ". конец " . $printedDateTime . $memory;

        echo "$statusBarText  ";

        flush();

        if ($done == $total)
        {
            echo "\n";
        }

    }

    //чтение цсв с колбеком
    public static function readCSV(string $path, array $options, callable $callback)
    {
        /*$options
         * DELIMITER - разделитель
         * USE_FIRST_ROW_AS_HEADER - использовать заголовок из 0го ряда для выдачи ассоциативных массивов
         * CONVERT_FROM - конвертация текста
         * */
        $F = fopen($path, 'r');
        if (!$F)
        {
            throw new \Exception('не могу прочитать файл ' . $path);
        }
        //заголовки пропустим
        $delimiter = $options[ 'DELIMITER' ] ?: ';';

        if ($options[ 'USE_FIRST_ROW_AS_HEADER' ])
        {
            $headers = fgetcsv($F, 1000, $delimiter);
        }

        while ($data = fgetcsv($F, 1000, $delimiter))
        {
            if ($options[ 'CONVERT_FROM' ])
            {
                $data = array_map(function ($text) use ($options) {
                    return iconv($options[ 'CONVERT_FROM' ], "UTF-8", $text);
                }, $data);
            }
            if ($options[ 'USE_FIRST_ROW_AS_HEADER' ])
            {
                foreach ($headers as $i => $header)
                {
                    $data[ $header ] = $data[ $i ];
                    unset($data[ $i ]);
                }
            }
            $callback($data);
        }
    }
    //уведомление в телегу.

    /*чтобы настроить - нужно создать приватный канал. приласить туда bitrixXpageMessage_bot и  написать
        /start domain.com, где это доменное имя сайта. это же имя нужно указать в админке
        */
    public static function telegramNotification($message): bool
    {
        if (!$message)
        {
            return false;
        }
        try
        {
            $HttpClient = new \Bitrix\Main\Web\HttpClient();
            $HttpClient->disableSslVerification();
            $HttpClient->post('https://controller.xpager.ru/api/telegram/channel/average/', [
                'message'  => (string)$message,
                'domain'   => \Bitrix\Main\Config\Option::get('main', 'server_name'),
                'debounce' => 600//со стороны сервиса задать как часто реагировать на сообщение с этим текстом
            ]);
            return true;
        } catch (\Throwable $e)
        {
            self::log([
                'message' => $message,
                'error'   => $e->getMessage(),
            ], __FUNCTION__);
            return false;
        }
    }

    public static function log($data, string $name = 'log', string $subDir = '',$withBacktrace = false): void
    {
        //для отслеживания очередности на хите
        static $logAmountOnHit = 1;
        if (!$name)
        {
            $name = 'log';
        }
        $log = "#start#---- " . date("d.m.Y H:i:s")." --- ".$_SERVER['UNIQUE_ID']." [$logAmountOnHit] ----\n";

        $logAmountOnHit++;
        $log .= print_r($data, 1);
        $log .= "#end#\n";
        if($withBacktrace)
        {
            $log.=\Xpage\Tools::getNiceBacktrace(2,true)."\n";
        }
        else{
            $log .= "------------------------------------\n";
        }

        $dir = $_SERVER[ 'DOCUMENT_ROOT' ] . '/local/logs/';
        if ($subDir)
        {
            $dir .= "$subDir/";
            if (!file_exists($dir))
            {
                mkdir($dir, BX_DIR_PERMISSIONS, true);
            }
        }
        $fileName = $name . ".log";
        $filePath = $dir . $fileName;
        if (file_exists($filePath))
        {
            if (filesize($filePath) > 1024 * 1024 * 2)
            {
                rename($filePath, $dir . $name . '_archive.log');
            }
        }
        file_put_contents($filePath, $log, FILE_APPEND);
    }

    public static function getNiceBacktrace(int $skipLevels = 1,$skipBitrixFiles = false): string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        print_r($backtrace);
        try
        {
            while ($skipLevels-- > 0 && count($backtrace) > 0)
            {
                array_shift($backtrace);
            }

            $resultTmp = array_map(static function ($backTraceItem) use($skipBitrixFiles) {

                $pathFromRoot = mb_substr($backTraceItem[ 'file' ], mb_strlen($_SERVER[ 'DOCUMENT_ROOT' ]));
                if (mb_stripos($pathFromRoot, '/bitrix') !== false && mb_stripos($backTraceItem[ 'class' ],'xpage')===false)
                {
                    if($skipBitrixFiles)
                    {
                        return '..bitrix..';
                    }
                    return $pathFromRoot;
                }
                return implode('', [
                    $pathFromRoot,
                    ' ',
                    $backTraceItem[ 'class' ],
                    $backTraceItem[ 'type' ],
                    $backTraceItem[ 'function' ],
                    '() [строка ',
                    $backTraceItem[ 'line' ],
                    ']',
                ]);
            }, $backtrace);
            $resultTmp = array_filter($resultTmp);
            $resultTmp = array_reverse($resultTmp);
        } catch (\Throwable $e)
        {

        }
        return implode(' ---> ', $resultTmp) ?: '';
    }

    //чистка старого битриксового кеша
    public static function cleanExpiredCache(): string
    {
        $agentReturn = '\Xpage\Tools::cleanExpiredCache();';
        if (!class_exists("CFileCacheCleaner"))
        {
            require_once($_SERVER[ "DOCUMENT_ROOT" ] . "/bitrix/modules/main/classes/general/cache_files_cleaner.php");
        }
        $currentTime = mktime();

        //Работаем со всем кешем
        $obCacheCleaner = new \CFileCacheCleaner("all");
        if (!$obCacheCleaner->InitPath(''))
        {
            //Произошла ошибка
            return $agentReturn;
        }
        $obCacheCleaner->Start();
        while ($file = $obCacheCleaner->GetNextFile())
        {
            if (!is_string($file))
            {
                continue;
            }
            $date_expire = $obCacheCleaner->GetFileExpiration($file);
            if ($date_expire)
            {
                if ($date_expire < $currentTime)
                {
                    unlink($file);
                }
            }
        }
        return $agentReturn;

    }

    public static function closeByIp():void
    {
        if(LOCAL_DEPLOYMENT)
        {
            return;
        }
        if (strpos($_SERVER['REQUEST_URI'],'/api/')!==false) {
            return;
        }
        //чтобы агенты корректно работали
        if(php_sapi_name()==='cli')
        {
            return;
        }
        $availableList = [ '94.181.33.167', '95.78.156.131', '94.140.229.214', '195.69.219.182' ];
        foreach ($availableList as $ip)
        {
            if($_SERVER[ 'REMOTE_ADDR' ]==$ip)
            {
                return;
            }
        }
        http_response_code(403);
        die('У вас нет доступа');
    }

    public static function getEnumVariations(int $fieldId): array
    {

        $cacheKey = __FUNCTION__ . $fieldId;
        $cacheHandler = \Bitrix\Main\Application::getInstance()->getManagedCache();
        if ($cacheHandler->read(60 * 60 * 24 * 7, $cacheKey, 'b_user_field_enum'))
        {
            $result = $cacheHandler->get($cacheKey);
        }
        else
        {
            $request = \CUserFieldEnum::GetList([], [ 'USER_FIELD_ID' => $fieldId ]);
            $result = [];
            while ($data = $request->GetNext(true, false))
            {
                $result[] = [
                    'value'  => $data[ 'ID' ],
                    'text'   => ucfirst(mb_strtolower($data[ 'VALUE' ])),
                    'xml_id' => $data[ 'XML_ID' ],
                    'sort'   => (int)$data[ 'SORT' ],
                ];
            }
            if ($result)
            {
                $cacheHandler->set($cacheKey, $result);
            }

        }
        return $result;
    }

    public static function getEnumDataById(int $enumId): array
    {

        $cacheKey = __FUNCTION__;
        $cacheHandler = \Bitrix\Main\Application::getInstance()->getManagedCache();
        if ($cacheHandler->read(60 * 60 * 24 * 7, $cacheKey, 'b_user_field_enum'))
        {
            $result = $cacheHandler->get($cacheKey);
        }
        else
        {
            $request = \CUserFieldEnum::GetList([], []);
            $result = [];
            while ($data = $request->GetNext(true, false))
            {
                $result[ $data[ 'ID' ] ][] = [
                    'value'  => $data[ 'ID' ],
                    'text'   => ucfirst(mb_strtolower($data[ 'VALUE' ])),
                    'xml_id' => $data[ 'XML_ID' ],
                    'sort'   => (int)$data[ 'SORT' ],
                ];
            }
            if ($result)
            {
                $cacheHandler->set($cacheKey, $result);
            }

        }
        return $result[ $enumId ] ?: [];


    }

    public static function objectsMap(string $methodName, array $objects): array
    {
        $result = [];
        foreach ($objects as $Object)
        {
            $result[] = $Object->{$methodName}();
        }
        return $result;

    }

    //через апи проверим, является ли дата рабочим днём
    public static function isWorkingDay(string $date): bool
    {
        $correctDate = date('Y-m-d', strtotime($date));

        $cacheKey = $correctDate;
        $CacheHandler = \Bitrix\Main\Application::getInstance()->getManagedCache();
        //на год кеш
        if($CacheHandler->read(60 * 60 * 24 * 30, $cacheKey,'workingdays'))
        {
            $result = $CacheHandler->get($cacheKey);
        }
        else
        {
            $Client = new \Bitrix\Main\Web\HttpClient;
            $Client->setTimeout(5);
            $data = $Client->get('https://isdayoff.ru/' . $correctDate);
            $result = [
                'w' => $data !== '1',
            ];
            if($result)
            {
                $CacheHandler->setImmediate($cacheKey,$result);
            }
        }
        return $result[ 'w' ];

    }

    /** проверка ссылок на сторонние сайты */
    public static function checkLinkOutput($link = false)
    {

        if($link === false)
        {
            return "";
        }
        $target = "";
        if(mb_strripos($link, "https://") !== false)
        {
            $target = ' target="_blank" rel="nofollow"';
        }
        else
        {
            //для ссылок на сторонние сайты, если не указан протокол
            $arLink = explode("/", $link);
            if(mb_strripos($arLink[0], ".") !== false)
            {
                $target = ' target="_blank" rel="nofollow"';
            }

        }

        return $target;
    }

    public static function parserPhone(string $phone)
    {

        /** @var  \Bitrix\Main\PhoneNumber\Parser $parsedPhone */
        $parsedPhone = Parser::getInstance()->parse($phone);

        return $parsedPhone;
    }

    public static function parserPhoneFormat(string $phone)
    {
        if(empty($phone)){return $phone;}

        /** @var  \Bitrix\Main\PhoneNumber\Parser $parsedPhone */
        $parsedPhone = self::parserPhone($phone);
        $phoneFormat = $parsedPhone->format(Format::E164);

        return $phoneFormat;
    }

}
