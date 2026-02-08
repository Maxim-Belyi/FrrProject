<?php
/*
 * Здесь лучше регистрировать события и прочие обработчики
 * */
namespace Xpage;
class Local
{
    //регистрация событий
    public static function registerEventsHandlers(): void
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->addEventHandler("iblock", "OnBeforeIBlockElementAdd", ["\Xpage\Events","OnBeforeIblockElementAdd"]);
        $eventManager->addEventHandler("iblock", "OnAfterIblockElementAdd", ["\Xpage\Events","OnAfterIblockElementAdd"]);

        \Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnBeforeProlog", ["\Xpage\Events", "OnBeforePrologHandler"]);
        \Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnEpilog", ["\Xpage\Events", "OnEpilogHandler"]);

        //выключили, потому что прелоадер не работал с этой штукой
        //\Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", [__CLASS__, "compressContent"]);

    }
}