<?php

namespace Xpage\Api;

class Handler
{
    private static $message = null;//сообщение ответа в апи

    private array $dynamicParams = [];

    private $callback = null;

    private bool $needAuth;
    public string $exceptionMessage = '';

    private \Xpage\Api\Cache $cache;

    private array $handlerParams = [];
    private string $foundHandlerPath;

    private bool    $enabledDebugLogs       = false;
    private ?string $debugLogsRequestMethod = null;

    public function __construct()
    {

    }

    public function handlerGet(string $endPoint, array $params = []):void
    {
        $params[ 'method' ] = 'GET';
        $this->registerHandler($endPoint, $params);
    }

    public function handlerPost(string $endPoint, array $params = []):void
    {
        $params[ 'method' ] = 'POST';
        $this->registerHandler($endPoint, $params);
    }

    //проверяем наш ли это случай
    private function registerHandler($routePath, $params = []):void
    {
        try
        {
            $method = $params[ 'method' ] ?: 'GET';
            $params[ 'needAuth' ] = $params[ 'needAuth' ] ?? true;

            if ($_SERVER[ 'REQUEST_METHOD' ] !== $method)
            {
                return;
            }

            static $handlerFound;

            if ($handlerFound)
            {
                return;
            }
            $regular = preg_replace_callback([ "@{([^/]+)}@" ], $this->pregCallback(...), $routePath);
            $regexp = "@^$regular$@ui";

            $host = '';
            if(isset($_SERVER[ 'SCRIPT_URL' ]))
            {
                $host = $_SERVER[ 'SCRIPT_URL' ];
            }
            elseif (isset($_SERVER[ 'REDIRECT_URL' ]) )
            {
                $host = $_SERVER[ 'REDIRECT_URL' ];
            }
            else{
                throw new \Exception('Не удалось получить URL');
            }

            $handlerFound = preg_match($regexp, $host, $matches);

            if ($handlerFound)
            {
                $this->foundHandlerPath = $routePath;
                $this->handlerParams = $params;

                if(isset($params[ 'callback' ]))
                {
                    $this->callback = $params[ 'callback' ];
                }

                $this->needAuth = !empty($params[ 'needAuth' ]);

                if(isset($params[ 'exceptionMessage' ]))
                {
                    $this->exceptionMessage = (string)$params[ 'exceptionMessage' ];
                }

                foreach ($matches as $key => $val)
                {
                    if (!is_numeric($key))
                    {
                        $this->dynamicParams[ $key ] = $val;
                    }
                }

                //кеш только если это гет запрос, не требующий авторизацию
                if(isset($params[ 'cache' ]) && !$this->needAuth && $_SERVER[ 'REQUEST_METHOD' ] === 'GET')
                {
                    $this->cache = new \Xpage\Api\Cache();
                    $this->cache->setEndPoint($routePath);
                    $this->cache->setDynamicParams([$_GET, $this->dynamicParams ?? []]);
                    $this->cache->setTtl($params[ 'cache' ]['ttl']);
                    $this->cache->setKey($params[ 'cache' ]['key']);
                }
            }

        } catch (\Throwable $e)
        {
            \Xpage\Tools::log([
                'server'=>$_SERVER,
                'e'=>$e->getMessage()
            ],__FUNCTION__,'errors');
        }
    }

    public function requireHandlersFromFile(string $fullPath):void
    {
        if(!$fullPath)
        {
            return;
        }
        $callBack = require $fullPath;
        call_user_func($callBack, $this);
    }

    /**
     * @return string
     */
    public function getFoundHandlerPath(bool $sanitizeForFilename = false): string
    {
        $path = $this->foundHandlerPath??'';
        if($sanitizeForFilename)
        {
            // Сначала обрежем начальный и конечный слэш
            $trimmedRoutePath = trim($path, '/');

            // Замена всех недопустимых символов на "_"
            $sanitizedRoutePath = preg_replace('/[^a-zA-Z0-9-_]/', '_', $trimmedRoutePath);
            return $sanitizedRoutePath;
        }
        return $path;
    }

    private function pregCallback(array $matches): string
    {
        $paramName = $matches[ 1 ];
        return "(?P<$paramName>[^/]+)";
    }

    public function execute()
    {
        try
        {
            if (isset($this->cache))
            {
                if ($cachedData = $this->cache->getData())
                {
                    return $cachedData;
                }
            }
        }
        catch (\Throwable $e)
        {

        }

        //для мусорных хитов, где не требуется действие с пользователем
        if(isset($this->handlerParams['readonly-session']) && $this->handlerParams['readonly-session'])
        {
            define('BX_SECURITY_SESSION_VIRTUAL', true);
        }

        //убрать подключение ядра
        if(!isset($this->handlerParams['noCore']) || !$this->handlerParams['noCore'])
        {
            $beforeTimer = microtime(1);
            require($_SERVER[ "DOCUMENT_ROOT" ] . "/bitrix/modules/main/include/prolog_before.php");
            header("Server-Timing: prolog;dur=".round((microtime(1)-$beforeTimer)*1000,2));

            if(!empty($this->handlerParams['groups']) && !\CSite::InGroup($this->handlerParams['groups']))
            {
                throw new \Exception('Доступ к эндпоинту ограничен');
            }

            if (!empty($this->needAuth) && !$GLOBALS[ 'USER' ]->IsAuthorized())
            {
                throw new \Exception('Ошибка доступа');
            }
        }

        if (!$this->callback)
        {
            throw new \Exception('Не найден обработчик для эндпоинта');
        }

        ob_get_clean();

        if ($_SERVER[ 'REQUEST_METHOD' ] === 'GET')
        {
            $result = ($this->callback)($this->dynamicParams, $_GET);

            if(isset($this->cache))
            {
                $this->cache->setResult($result);
            }

        }
        elseif ($_SERVER[ 'REQUEST_METHOD' ] === 'POST')
        {
            $postData = $_POST;
            if (!$postData)
            {
                $content = file_get_contents('php://input');
                //на случай если запрос в формате json
                if ($content && $decodedJson = json_decode($content, 1))
                {
                    $postData = $decodedJson;
                }
            }
            $result = ($this->callback)($this->dynamicParams, $postData);

        }
        else
        {
            http_response_code(405);
            die();
        }
        if($this->isLogsEnabled())
        {
            $this->logRouteData($result);
        }

        return $result;
    }

    public static function jsonEncode($res)
    {
        if (is_array($res))
        {
            array_walk_recursive($res, function (&$el, $key) {
                if (!empty($el) && ($el instanceof \Bitrix\Main\Type\DateTime || $el instanceof \DateTime || $el instanceof \Bitrix\Main\Type\Date))
                {
                    $el = $el->format('c');
                }
            });
        }
        //JSON_FORCE_OBJECT для предсказуемости результата
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public static function getMessage(): ?string
    {
        return self::$message;
    }

    //установить сообщение ответа. если требуется
    public static function setMessage(string $message): void
    {
        self::$message = $message;
    }

    //если надо записывать логи для методов
    public function enableDebugLogs(?string $method = null)
    {
        $this->enabledDebugLogs = true;
        $this->debugLogsRequestMethod = $method;
    }

    public function isLogsEnabled():bool
    {
        if(empty($this->enabledDebugLogs))
        {
            return false;
        }
        return $_SERVER[ 'REQUEST_METHOD' ] === $this->debugLogsRequestMethod;
    }

    //--------тестовые методы для отладки----------------------------------------------------------------
    public static function test($urlParams = []): array
    {
        throw new \Exception('test');
        return $_SERVER;
    }
    public static function longTestMethod():array
    {
        sleep(5);
        return [
            'date'=>date('c'),
            'test'=>range('a','z')[rand(0,25)] . range('a','z')[rand(0,25)] . range('a','z')[rand(0,25)]
        ];
    }

    //метод для тестирования кеша
    public static function randomData() {
        $result = [];
        foreach (range(0, 500) as $I)
        {
            $result[]=[
                'num'=>$I,
                'rand'=>md5(microtime(1)),

            ];
        }
        return $result;
    }

    public function logRouteData( $result,string $logDir = 'api_debug')
    {
        $logData = [
            'result' => $result,
            'server' => $_SERVER,
            'request' => $_REQUEST,
        ];
        \Xpage\Tools::log($logData, $this->getFoundHandlerPath(true), $logDir);

    }



}