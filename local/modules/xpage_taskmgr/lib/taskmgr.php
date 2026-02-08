<?php

	namespace Xpage\Taskmgr;

	use Bitrix\Main\Type;

	class TaskMgr
	{
		public $log_id;
		public $currentTaskData;
		/**
		 * @var TaskBase
		 */
		public $command;
		public $is_force;
		public $message_variables = [];
		public $scriptParams      = [];

		public function __construct()
		{
			global $argv;
			$arScriptParams = [];
			if ($argv)
			{
				foreach ($argv as $key => $param)
				{
					if (stripos($param, '.php') !== false)
					{
						continue;
					}
					if (stripos($param, '=') !== false)
					{
						$param                     = explode('=', $param);
						$arScriptParams[$param[0]] = $param[1];
					}
					else
					{
						$arScriptParams['args'] [] = $param;
					}
				}
			}

			$this->scriptParams = $arScriptParams;
		}

		public function initTaskLog($logId = null)
		{
			if (!$logId)
			{
				$logId = LogTable::add([
										   'TASK_ID' => $this->currentTaskData['ID'],
									   ])->getID();
			}
			$this->log_id = $logId;
			return $this->log_id;
		}

		public function initTask($taskID)
		{
			$this->currentTaskData = TaskTable::getRowById($taskID);
			$arCommands            = $this->getCommands();
			if (!empty($arCommands[$this->currentTaskData['COMMAND']]))
			{
				$commandClass = $arCommands[$this->currentTaskData['COMMAND']]['CLASS'];

				$this->command = new $commandClass;
				$this->command->setId($taskID);

				$this->command->setTaskMgr($this);
			}
			return $this->currentTaskData;
		}

		public function setMessageVariable($name, $value)
		{
			$this->message_variables[$name] = $value;
		}

		public function runTask($is_force = false)
		{
			$this->is_force = $is_force;
			$this->initTaskLog();
			if ($this->currentTaskData['RUN_FORCED'] == 'Y')
			{
				$this->addLogMessage('Инициирован ручной запуск');
				\Xpage\Taskmgr\TaskTable::update($this->currentTaskData['ID'], [
					'RUN_FORCED' => 'N',
				]);
			}
			try
			{
				$memoryUsageMb = round(memory_get_peak_usage(true) / (1024 * 1024), 2);

				$this->addLogMessage('До начала скрипт использует - ' . $memoryUsageMb . 'мб оперативки', $this->log_id);
				//здесь происходит старт задачи
				if (!empty($this->command))
				{
					$this->command->run();
				}
				$memoryUsageMb = round(memory_get_peak_usage(true) / (1024 * 1024), 2);
				$this->addLogMessage('Пиковое значение памяти - ' . $memoryUsageMb . 'мб оперативки', $this->log_id);
				$arLog = LogTable::getRowById($this->log_id);
				if (!in_array($arLog['STATUS'], ['error', 'success',]))
				{
					$arLog['STATUS']       = 'success';
					$arLog['DATETIME_END'] = new Type\DateTime();
					LogTable::update($this->log_id, [
						'STATUS'       => $arLog['STATUS'],
						'DATETIME_END' => $arLog['DATETIME_END'],
					]);
				}
				switch ($arLog['STATUS'])
				{
					case 'success':
						$this->onSuccess();
						break;
					case 'error':
						$this->onError();
						break;

					default:
						break;
				}
			}
			catch (\Throwable $e)
			{
				$this->addLogMessage($e->getMessage(), $this->log_id);
				$this->onError();

			}

		}

		public function check()
		{
			if (!empty($this->scriptParams['command']) && stripos(get_class($this->command), $this->scriptParams['command']) === false)
			{
				return false;
			}
			$arIncompletedTasks = $this->getIncompletedTasks();
			foreach ($arIncompletedTasks as $arIncompletedTask)
			{
				if (($arIncompletedTask['PRIORITY'] > $this->currentTaskData['PRIORITY']) && ($this->currentTaskData['TASK_GROUP'] == $arIncompletedTask['TASK_GROUP']) && ($this->currentTaskData['ID'] != $arIncompletedTask['ID']))
				{
					return false;
				}
			}

			$arTaskLastLog = LogTable::getRow([
												  'filter' => [
													  '=TASK_ID' => $this->currentTaskData['ID'],
												  ],
												  'order'  => [
													  'DATETIME_START' => 'DESC',
												  ],
												  'limit'  => 1,
											  ]);

			if ($this->currentTaskData['AUTORUN'] == 'Y')
			{
				if (in_array($arTaskLastLog['STATUS'], ['process']))
				{
					return false;
				}

				if (!empty($this->currentTaskData['TASK_INTERVAL']) && !empty($arTaskLastLog['DATETIME_END']))
				{
					// Получаем временную метку начала задачи
					$lastStartTimestamp = $arTaskLastLog['DATETIME_END']->getTimestamp();
					// Получаем текущее время
					$nowTimestamp = (new \DateTime())->getTimestamp();
					// Вычисляем разницу во времени
					$diff = $nowTimestamp - $lastStartTimestamp;
					// Проверяем, меньше ли разница заданного интервала
					if ($diff < $this->currentTaskData['TASK_INTERVAL'])
					{
						return false;//Если меньше, то возвращаем ложь, чтобы задача не запускалась
					}
				}

				if (method_exists($this->command, 'runCondition'))
				{
					if ($this->command->runCondition() === true)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				if (empty($arTaskLastLog))
				{
					return true;
				}
				$dtNow           = new \DateTime();
				$dtTaskTimeStart = new \DateTime($this->currentTaskData['TIME_START']);
				// интервал от установленного на сегодня времени запуска до текущего момента
				$intervalTaskTimeStart2Now = $dtTaskTimeStart->diff($dtNow);

				if ($arTaskLastLog['DATETIME_START'])
				{
					$dtTaskLastStart = new \DateTime($arTaskLastLog['DATETIME_START']);
					// интервал от последнего запуска до текущего момента
					$intervalLast2Now = $dtTaskLastStart->diff($dtNow);
					// интервал от последнего запуска до установленного на сегодня времени запуска
					$intervalLast2TaskTimeStart = $dtTaskLastStart->diff($dtTaskTimeStart);
				}
				if ($arTaskLastLog['STATUS'] == 'process')
				{
					return false;
				}
				else if ($arTaskLastLog['STATUS'] == 'error')
				{
					// если пришло время запуска
					// и последний успешный запуск был произведён до установленного на сегодня времени запуска
					if (($intervalTaskTimeStart2Now->format('%R') == '+') && ($intervalLast2TaskTimeStart->format('%R') == '+'))
					{
						return true;
					}
					// если лимит попыток не исчерпан
					else if ($this->currentTaskData['UNSUCCESSFUL_RUN_COUNT'] < $this->currentTaskData['NOTIFICATIONS_COUNT'])
					{
						// если прошло достаточно минут с последней попытки
						if ($intervalLast2Now->format('%i') > 20)
						{
							return true;
						}
					}
					return false;
				}
				else if ($arTaskLastLog['STATUS'] == 'success')
				{
					// если пришло время запуска
					if ($intervalTaskTimeStart2Now->format('%R') == '+')
					{
						// и последний успешный запуск был произведён до установленного на сегодня времени запуска
						if ($intervalLast2TaskTimeStart->format('%R') == '+')
						{
							return true;
						}
					}
				}
			}
		}

		public function getIncompletedTasks()
		{
			$arIncompletedTasks = [];
			$rsTasks            = TaskTable::getList([
														 'filter' => [
															 'ACTIVE' => 'Y',
														 ],
													 ]);
			while ($arTask = $rsTasks->fetch())
			{
				$arTaskLastLog = LogTable::getList([
													   'filter' => [
														   'TASK_ID' => $arTask['ID'],
													   ],
													   'order'  => [
														   'ID' => 'DESC',
													   ],
													   'limit'  => 1,
												   ])->fetch();
				if (in_array($arTaskLastLog['STATUS'], ['process', 'error',]))
				{
					$arIncompletedTasks [] = $arTask;
				}
			}
			return $arIncompletedTasks;
		}

		public function getCommands()
		{
			return $this->initCommands();
		}

		public function initCommands()
		{
			$arCommands  = [];
			$arTaskFiles = glob($_SERVER['DOCUMENT_ROOT'] . '/local/modules/xpage_taskmgr/tasks/*');
			foreach ($arTaskFiles as $filepath)
			{
				$arCommand  = [];
				$arTaskFile = pathinfo($filepath);
				$className  = implode('', array_filter(array_map(function ($word)
				{
					return ucfirst($word);
				}, explode('_', $arTaskFile['filename']))));
				include_once($filepath);
				if (class_exists($className))
				{
					$arCommand['NAME']                 = $className::getTaskName();
					$arCommand['COMMAND']              = $className;
					$arCommand['CLASS']                = $className;
					$arCommand['FILE']                 = $arTaskFile;
					$arCommands[$arCommand['COMMAND']] = $arCommand;
				}
			}
			return $arCommands;
		}

		public function addLogMessage($message, $logID = null)
		{
			if (!$logID)
			{
				$logID = $this->log_id;
			}
			$arFields = [
				'LOG_ID'   => $logID,
				'MESSAGE'  => $message,
				'DATETIME' => new Type\DateTime(),
			];
			LogMessagesTable::add($arFields);
		}

		public function setLogStatus($status, $logID = null)
		{
			if (!$logID)
			{
				$logID = $this->log_id;
			}
			LogTable::update($logID, [
				'STATUS' => $status,
			]);
		}

		public function onError()
		{
			TaskTable::update($this->currentTaskData['ID'], [
				'UNSUCCESSFUL_RUN_COUNT' => ($this->currentTaskData['UNSUCCESSFUL_RUN_COUNT'] + 1),
			]);
			if ($this->currentTaskData['ENABLE_SMS'] == 'Y')
			{
				$phones = $this->currentTaskData['SMS_PHONES'];
				$phones = str_ireplace("\n", ',', $phones);
				$phones = str_ireplace("\s", '', $phones);
				$phones = str_ireplace("\t", '', $phones);
				$phones = array_filter(explode(',', $phones));
				if (!empty($phones))
				{
					if (!empty($this->currentTaskData['SMS_MESSAGE_ERROR']))
					{
						$this->sendSMS($this->currentTaskData['SMS_MESSAGE_ERROR'], $phones);
					}
				}
			}
			if ($this->currentTaskData['ENABLE_MAIL'] == 'Y')
			{
				$emails = $this->currentTaskData['EMAIL_ADDESSES'];
				$emails = str_ireplace("\n", ',', $emails);
				$emails = str_ireplace("\s", '', $emails);
				$emails = str_ireplace("\t", '', $emails);
				$emails = array_filter(explode(',', $emails));
				$emails = implode(',', $emails);
				if (!empty($emails))
				{
					$arMailFields = [
						'TASK_NAME' => $this->currentTaskData['NAME'],
						'STATUS'    => 'error',
						'EMAIL'     => $emails,
						'MESSAGE'   => '',
					];
					if ((!empty($this->currentTaskData['EMAIL_MESSAGE_ERROR'])))
					{
						$arMailFields['MESSAGE'] = $this->currentTaskData['EMAIL_MESSAGE_ERROR'];
						$arMailFields['MESSAGE'] = str_ireplace(  array_map(function ($key)
						{
							return "#{$key}#";
						}, array_keys($this->message_variables)), $this->message_variables, $arMailFields['MESSAGE']);
						$arMailFields['MESSAGE'] = str_ireplace("\n", '<br/>', $arMailFields['MESSAGE']);
					}
					$this->sendMail($arMailFields);
				}
			}
		}

		public function onSuccess()
		{
			TaskTable::update($this->currentTaskData['ID'], [
				'UNSUCCESSFUL_RUN_COUNT' => 0,
			]);
			if ($this->currentTaskData['ENABLE_SMS'] == 'Y')
			{
				$phones = $this->currentTaskData['SMS_PHONES'];
				$phones = str_ireplace("\n", ',', $phones);
				$phones = str_ireplace("\s", '', $phones);
				$phones = str_ireplace("\t", '', $phones);
				$phones = array_filter(explode(',', $phones));
				if (!empty($phones))
				{
					if (!empty($this->currentTaskData['SMS_MESSAGE_OK']))
					{
						$this->sendSMS($this->currentTaskData['SMS_MESSAGE_OK'], $phones);
					}
				}
			}
			if ($this->currentTaskData['ENABLE_MAIL'] == 'Y')
			{
				$emails = $this->currentTaskData['EMAIL_ADDESSES'];
				$emails = str_ireplace("\n", ',', $emails);
				$emails = str_ireplace("\s", '', $emails);
				$emails = str_ireplace("\t", '', $emails);
				$emails = array_filter(explode(',', $emails));
				$emails = implode(',', $emails);
				if (!empty($emails))
				{
					$arMailFields = [
						'TASK_NAME' => $this->currentTaskData['NAME'],
						'STATUS'    => 'success',
						'EMAIL'     => $emails,
						'MESSAGE'   => '',
					];
					if ((!empty($this->currentTaskData['EMAIL_MESSAGE_OK'])))
					{
						$arMailFields['MESSAGE'] = $this->currentTaskData['EMAIL_MESSAGE_OK'];
						$arMailFields['MESSAGE'] = str_ireplace(  array_map(function ($key)
						{
							return "#{$key}#";
						}, array_keys($this->message_variables)), $this->message_variables, $arMailFields['MESSAGE']);
						$arMailFields['MESSAGE'] = str_ireplace("\n", '<br/>', $arMailFields['MESSAGE']);
					}
					$this->sendMail($arMailFields);
				}
			}
		}

		public function sendSMS($message, $phones)
		{
			if (!empty($this->message_variables['IMPORT_FILE_DATE']))
			{
				$message .= ' Дата архива: ' . $this->message_variables['IMPORT_FILE_DATE'];
			}
			$message = str_ireplace(                  array_map(function ($key)
			{
				return "#{$key}#";
			}, array_keys($this->message_variables)), $this->message_variables, $message);
			foreach ($phones as $phone)
			{
				$phone = trim($phone);
				$res   = \Xpage\IntisSMS::send($message, $phone);
				$this->addLogMessage("Ответ смс-сервиса:\n" . print_r($res, 1));
			}
			// if (\CModule::IncludeModule("rarus.sms4b")) {
			// 	global $SMS4B;
			// 	$SMS4B->SendSmsPack($message, $phones);
			// }
			return false;
		}

		public function sendMail($arMailFields)
		{
			return \CEvent::Send('XPAGE_TASKMGR', SITE_ID, $arMailFields);
		}
	}
