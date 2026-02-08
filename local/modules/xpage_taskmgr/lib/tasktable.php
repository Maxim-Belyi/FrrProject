<?php

namespace Xpage\Taskmgr;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\DB\MssqlConnection;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class TaskTable extends Entity\DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'xpage_taskmgr_tasks';
	}

	public static function getMap()
	{
		return [
			'ID'                     => new Entity\IntegerField('ID', [
				'primary'      => true,
				'autocomplete' => true,
			]),
			'NAME'                   => new Entity\StringField('NAME', [
			]),
			'ACTIVE'                 => new Entity\BooleanField('ACTIVE', [
				'values'        => ['N', 'Y'],
				'default_value' => 'Y',
			]),
			'PRIORITY'               => new Entity\IntegerField('PRIORITY', [
			]),
			'TIME_START'             => new Main\Entity\StringField('TIME_START', [
			]),
			'COMMAND'                => new Main\Entity\StringField('COMMAND', [
			]),
			'ENABLE_SMS'             => new Entity\BooleanField('ENABLE_SMS', [
				'values'        => ['N', 'Y'],
				'default_value' => 'Y',
			]),
			'ENABLE_MAIL'            => new Entity\BooleanField('ENABLE_MAIL', [
				'values'        => ['N', 'Y'],
				'default_value' => 'Y',
			]),
			'SMS_MESSAGE_OK'         => new Main\Entity\TextField('SMS_MESSAGE_OK', [
			]),
			'SMS_MESSAGE_ERROR'      => new Main\Entity\TextField('SMS_MESSAGE_ERROR', [
			]),
			'EMAIL_MESSAGE_OK'       => new Main\Entity\TextField('EMAIL_MESSAGE_OK', [
			]),
			'EMAIL_MESSAGE_ERROR'    => new Main\Entity\TextField('EMAIL_MESSAGE_ERROR', [
			]),
			'SMS_PHONES'             => new Main\Entity\TextField('SMS_PHONES', [
			]),
			'EMAIL_ADDESSES'         => new Main\Entity\TextField('EMAIL_ADDESSES', [
			]),
			'TASK_INTERVAL'          => new Entity\IntegerField('TASK_INTERVAL', [
			]),
			'NOTIFICATIONS_COUNT'    => new Entity\IntegerField('NOTIFICATIONS_COUNT', [
				'default_value' => 0,
			]),
			'UNSUCCESSFUL_RUN_COUNT' => new Entity\IntegerField('UNSUCCESSFUL_RUN_COUNT', [
				'default_value' => 0,
			]),
			'TASK_GROUP'             => new Entity\IntegerField('TASK_GROUP', [
				'default_value' => 1,
			]),
			'RUN_FORCED'             => new Entity\BooleanField('RUN_FORCED', [
				'values'        => ['N', 'Y'],
				'default_value' => 'N',
			]),
			'AUTORUN'                => new Entity\BooleanField('AUTORUN', [
				'values'        => ['N', 'Y'],
				'default_value' => 'N',
			]),
		];
	}

}
