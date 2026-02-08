<?php
use Bitrix\Main\Entity;
CModule::AddAutoloadClasses('xpage_taskmgr', array(
    'Xpage\Taskmgr\TaskMgr'           => 'lib/taskmgr.php',
    '\Xpage\Taskmgr\TaskMgr'          => 'lib/taskmgr.php',
    'Xpage\Taskmgr\TaskTable'         => 'lib/tasktable.php',
    '\Xpage\Taskmgr\TaskTable'        => 'lib/tasktable.php',
    'Xpage\Taskmgr\LogTable'          => 'lib/logtable.php',
    '\Xpage\Taskmgr\LogTable'         => 'lib/logtable.php',
    'Xpage\Taskmgr\LogMessagesTable'  => 'lib/logmessagestable.php',
    '\Xpage\Taskmgr\LogMessagesTable' => 'lib/logmessagestable.php',
    'Xpage\Taskmgr\Watcher'           => 'lib/watcher.php',
    '\Xpage\Taskmgr\Watcher'          => 'lib/watcher.php',
    'Xpage\Taskmgr\ImportXml'         => 'lib/importxml.php',
    '\Xpage\Taskmgr\ImportXml'        => 'lib/importxml.php',
    'Xpage\Taskmgr\TaskBase'          => 'lib/taskbase.php',
    '\Xpage\Taskmgr\TaskBase'         => 'lib/taskbase.php',
    'Xpage\CatalogGroup2GroupTable'   => 'lib/cataloggroup2group.php',
    '\Xpage\CatalogGroup2GroupTable'  => 'lib/cataloggroup2group.php',
));
