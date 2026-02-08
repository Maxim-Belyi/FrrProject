<?php
/*Сюда помещаем агентов.
Заранее есть методы, которые выполняются раз в минуту/час/день.
 Туда можно вставлять вызовы методов которые вам нужны
*/

namespace Xpage;
class Agents
{
    //запуск раз в минуту
    public static function agentEachMinute(): string
    {
        $agents=[
            [
                'callback'=>['',''],
                'params'=>[]
            ]
        ];
        foreach ($agents as $agent) {
            if (!isset($agent['callback']) || !is_callable($agent['callback'])) {
                continue;
            }
            try
            {
                $agent['callback'](...$agent['params']);
            }
            catch (\Throwable $e)
            {
                Tools::log([$agent,$e->getMessage()],'agentErrors');
            }
        }
        return "\Xpage\Agents::agentEachMinute();";
    }

    //запуск раз в час
    public static function agentEachHour(): string
    {
        $agents=[
            [
                'callback'=>['\Xpage\Tools','cleanExpiredCache'],
                'params'=>[]
            ]
        ];
        foreach ($agents as $agent) {
            if (!isset($agent['callback']) || !is_callable($agent['callback'])) {
                continue;
            }
            try
            {
                $agent['callback'](...$agent['params']);
            }
            catch (\Throwable $e)
            {
                Tools::log([$agent,$e->getMessage()],'agentErrors');
            }
        }
        return "\Xpage\Agents::agentEachHour();";
    }

    //запуск раз в день
    public static function agentDaily(): string
    {

        $agents=[
            [
                'callback'=>['\Xpage\Api\Cache','clearAllCache'],
                'params'=>[60 * 60 * 24 * 3]
            ]
        ];
        foreach ($agents as $agent) {
            if (!isset($agent['callback']) || !is_callable($agent['callback'])) {
                continue;
            }
            try
            {
                $agent['callback'](...$agent['params']);
            }
            catch (\Throwable $e)
            {
                Tools::log([$agent,$e->getMessage()],'agentErrors');
            }
        }

        return "\Xpage\Agents::agentDaily();";
    }
}