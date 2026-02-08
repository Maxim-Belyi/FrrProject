<?php

namespace Xpage\Taskmgr;

class Watcher
{
    public static function run()
    {
        $rsTasks = TaskTable::getList(array(
            'order'  => array(
                'PRIORITY' => 'DESC',
            ),
            'filter' => array(
                'ACTIVE' => 'Y',
            ),
        ));
        while ($arTask = $rsTasks->fetch())
        {
            $taskMgr = new TaskMgr();
            if ($taskMgr->initTask($arTask[ 'ID' ]))
            {
                if ($arTask[ 'RUN_FORCED' ] == 'Y' || $taskMgr->check())
                {
                    $is_force = ($arTask[ 'RUN_FORCED' ] == 'Y');
                    $taskMgr->runTask($is_force);
                    break;
                }
            }
        }
    }

}
