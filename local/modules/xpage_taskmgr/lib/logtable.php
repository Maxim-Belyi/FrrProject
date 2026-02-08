<?php

namespace Xpage\Taskmgr;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\DB\MssqlConnection;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class LogTable extends Entity\DataManager
{
	public static function getFilePath() {
		return __FILE__;
	}

	public static function getTableName() {
		return 'xpage_taskmgr_log';
	}

	public static function getMap()	{
		return array(
      'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
      )),
      'TASK_ID' => new Entity\IntegerField('TASK_ID', array(
      )),
      'STATUS' => new Entity\StringField('STATUS', array(
				'default_value' => 'process',
      )),
      'DATETIME_START' => new Main\Entity\DatetimeField('DATETIME_START', array(
				'default_value' => new Main\Type\DateTime(),
      )),
      'DATETIME_END' => new Main\Entity\DatetimeField('DATETIME_END', array(
      )),
			'RUN_TYPE' => new Main\Entity\StringField('RUN_TYPE', array(
				'default_value' => 'auto',
			)),
		);
	}

}
