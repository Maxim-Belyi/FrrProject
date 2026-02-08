<?php

namespace Xpage\Taskmgr;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\DB\MssqlConnection;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class LogMessagesTable extends Entity\DataManager
{
	public static function getFilePath() {
		return __FILE__;
	}

	public static function getTableName() {
		return 'xpage_taskmgr_log_messages';
	}

	public static function getMap()	{
		return array(
      'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
      )),
      'LOG_ID' => new Entity\IntegerField('LOG_ID', array(
      )),
			'MESSAGE' => new Main\Entity\TextField('MESSAGE', array(
			)),
      'DATETIME' => new Main\Entity\DatetimeField('DATETIME', array(
				'default_value' => new Main\Type\DateTime(),
      )),
		);
	}

}
