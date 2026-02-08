<?php

	class XpageSimpleComponent extends CBitrixComponent
	{

		public function executeComponent()
		{
			global $APPLICATION;
			if (\CModule::IncludeModule('xpage_taskmgr'))
			{
				$this->processForms();
				$arParams                       = $this->arParams;
				$this->arResult                 = array_merge($this->arResult, $arParams);
				$this->arResult['LOG_URL']      = $APPLICATION->GetCurPageParam('action=log', ['action']);
				$this->arResult['ADD_TASK_URL'] = $APPLICATION->GetCurPageParam('action=addtask', ['action']);
				$this->arResult['LIST_URL']     = $APPLICATION->GetCurPageParam('action=tasks', ['action']);
				$taskMgr                        = new \Xpage\Taskmgr\TaskMgr;
				$this->arResult['COMMANDS']     = $taskMgr->getCommands();
				$this->arResult['TASKS']        = $this->getTasks();
				usort($this->arResult['TASKS'], function ($taskA, $taskB)
				{
					if ($taskA['TASK_GROUP'] - $taskB['TASK_GROUP'] !== 0)
					{
						return $taskA['TASK_GROUP'] - $taskB['TASK_GROUP'];
					}
					return $taskB['PRIORITY'] - $taskA['PRIORITY'];
				});
				$this->arResult['RUN_FORCED'] = [];
				foreach ($this->arResult['TASKS'] as $arTask)
				{
					if ($arTask['RUN_FORCED'] != 'Y')
					{
						continue;
					}
					$this->arResult['RUN_FORCED'][$arTask['ID']] = $arTask;
				}

				$this->arResult['ACTION'] = $this->getAction();

				if ($this->arResult['ACTION'] == 'edittask')
				{
					$this->arResult['TASK'] = $this->getTask($_REQUEST['TASK_ID']);
				}
				if ($this->arResult['ACTION'] == 'log')
				{
					$dateFormat = $GLOBALS['DB']->DateFormatToPHP(FORMAT_DATETIME);
					$date       = new DateTime();
					if (!empty($_REQUEST['log_date']))
					{
						$arDate = array_filter(explode('.', $_REQUEST['log_date']));
						if (checkdate($arDate[1], $arDate[0], $arDate[2]))
						{
							$date->setDate($arDate[2], $arDate[1], $arDate[0]);
						}
					}
					$date->setTime(0, 0);
					$date_from = $date->format($dateFormat);
					$date->setTime(23, 59);
					$date_to  = $date->format($dateFormat);
					$arFilter = [
						'><DATETIME_START' => [$date_from, $date_to],
					];
					if ((int)$_GET['taskId'])
					{
						$arFilter['=TASK_ID'] = $_GET['taskId'];
					}
					$this->arResult['LOG_DATE'] = $date->format('d.m.Y');
					$this->arResult['LOG']      = $this->getLog($arFilter);
				}
				if ($this->arResult['ACTION'] == 'RUN_TASK')
				{
					$taskID = $_REQUEST['TASK_ID'];
					if (is_numeric($taskID))
					{
						\Xpage\Taskmgr\TaskTable::update($taskID, [
							'RUN_FORCED' => 'Y',
						]);
						LocalRedirect($APPLICATION->GetCurPageParam('', ['TASK_ID', 'action',]));
					}
				}
			}

			$this->includeComponentTemplate();
		}

		public function getLog($arrFilter = [])
		{
			$arLogs = [];
			if (\CModule::IncludeModule('xpage_taskmgr'))
			{
				$arTasks  = $this->getTasks();
				$arFilter = [];
				$rsLogs   = \Xpage\Taskmgr\LogTable::getList([
																 'filter' => array_merge($arrFilter, $arFilter),
																 'order'  => [
																	 'ID' => 'DESC',
																 ],
															 ]);
				while ($arLog = $rsLogs->fetch())
				{
					$arLog['TASK'] = $arTasks[$arLog['TASK_ID']];
					if ($arLog['DATETIME_START'] && $arLog['DATETIME_END'])
					{
						$dtStart           = new \DateTime($arLog['DATETIME_START']);
						$dtEnd             = new \DateTime($arLog['DATETIME_END']);
						$intervalStart2End = $dtStart->diff($dtEnd);
						$arLog['INTERVAL'] = $intervalStart2End->format('%H ч %I мин %S сек');
					}
					$arLogs[$arLog['ID']] = $arLog;
				}
				$rsLogMessages = \Xpage\Taskmgr\LogMessagesTable::getList([
																			  'filter' => [
																				  'LOG_ID' => array_keys($arLogs),
																			  ],
																		  ]);
				while ($arLogMessage = $rsLogMessages->fetch())
				{
					$arLogs[$arLogMessage['LOG_ID']]['MESSAGES'] [] = $arLogMessage;
				}
			}
			return $arLogs;
		}

		public function getAction()
		{
			$action = 'tasks';
			if (!empty($_REQUEST['action']))
			{
				$action = $_REQUEST['action'];
			}
			return $action;
		}

		public function getTasks()
		{
			global $APPLICATION;
			$arTasks = [];
			if (\CModule::IncludeModule('xpage_taskmgr'))
			{
				$todayStart = (new \Bitrix\Main\Type\DateTime())->setTime(0, 0);
				$rsTasks    = \Xpage\Taskmgr\TaskTable::getList([
																	'order' => [
																		'PRIORITY'   => 'DESC',
																		'TIME_START' => 'ASC',
																		'ACTIVE'     => 'DESC',
																	],
																]);

				while ($arTask = $rsTasks->fetch())
				{
					$arTask['EDIT_URL']      = $APPLICATION->GetCurPageParam('action=edittask&TASK_ID=' . $arTask['ID'], [
						'TASK_ID',
						'action',
					]);
					$arTasks [$arTask['ID']] = $arTask;

					$lastTaskLog = \Xpage\Taskmgr\LogTable::getRow([
																	   'filter' => [
																		   '=TASK_ID'        => $arTask['ID'],
																		   '>DATETIME_START' => $todayStart,
																	   ],
																	   'group'  => ['TASK_ID'],
																	   'select' => [
																		   'TASK_ID',
																		   'COUNT'                  => new \Bitrix\Main\Entity\ExpressionField('COUNT', 'count(*)'),
																		   'EXP:MAX_DATETIME_START' => new \Bitrix\Main\Entity\ExpressionField('MAX_DATETIME_START', 'Max(DATETIME_START)'),
																		   'EXP:MAX_DATETIME_END'   => new \Bitrix\Main\Entity\ExpressionField('MAX_DATETIME_END', 'Max(DATETIME_END)'),
																	   ],
																   ]);

					$arTask['finished'] = false;
					$arTask['run']      = false;
					$arTask['color']    = '#BCB88A';
					$arTask['title']    = 'Не запускалась';
					$arTask['count']    = $lastTaskLog['COUNT'];

					//если задача началась, но еще не закончилась
					if ($lastTaskLog['EXP:MAX_DATETIME_START'] && (!$lastTaskLog['EXP:MAX_DATETIME_END'] || ($lastTaskLog['EXP:MAX_DATETIME_START'] > $lastTaskLog['EXP:MAX_DATETIME_END'])))
					{
						$arTask['run']        = true;
						$arTask['title']      = 'Запущена';
						$arTask['color']      = 'orange';
						$arTask['TIME_START'] = date('d.m.Y H:i', strtotime($lastTaskLog['DATETIME_START']));
					}

//                $lastExecuteTimestamp = strtotime(\Bitrix\Main\Application::getConnection()->queryScalar("select DATETIME_END from xpage_taskmgr_log where TASK_ID={$arTask[ 'ID' ]} and DATETIME_END is not null order by ID desc"));
					if ($lastTaskLog)
					{
						$arTask['finished']   = true;
						$arTask['title']      = 'Выполнена';
						$arTask['color']      = '#50C878';
						$arTask['TIME_START'] = $lastTaskLog['EXP:MAX_DATETIME_START']->format('d.m.Y H:i');
						if ($lastTaskLog['EXP:MAX_DATETIME_END'])
						{
							$arTask['TIME_END'] = $lastTaskLog['EXP:MAX_DATETIME_END']->format('d.m.Y H:i');
						}
						else
						{
							$arTask['TIME_END'] = '';
						}
					}
					else
					{
						//ищем прошлый запуск
						$lastTaskLog = \Xpage\Taskmgr\LogTable::getRow([
																		   'order'  => ['DATETIME_START' => 'DESC'],
																		   'filter' => [
																			   '=TASK_ID'        => $arTask['ID'],
																			   '!DATETIME_START' => false,
																		   ],
																		   'select' => [
																			   'TASK_ID',
																			   'DATETIME_START',
																			   'DATETIME_END',
																		   ],
																	   ]);
						if ($lastTaskLog)
						{
							//todo расчет последнего запуска
							$arTask['TIME_START'] = $lastTaskLog['DATETIME_START']->format('d.m.Y H:i');
							if ($lastTaskLog['DATETIME_END'])
							{
								$arTask['TIME_END'] = $lastTaskLog['DATETIME_END']->format('d.m.Y H:i');
							}
							else
							{
								$arTask['TIME_END'] = 'не завершена';
							}

						}

					}
					$arTasks[$arTask['ID']] = $arTask;
				}
			}
			return $arTasks;
		}

		public function getTask(int $id): array
		{
			$arTask = [];
			if (\CModule::IncludeModule('xpage_taskmgr'))
			{
				$arTask = \Xpage\Taskmgr\TaskTable::getRowById($id);
			}
			return $arTask;
		}

		public function processForms()
		{
			global $APPLICATION;
			if (empty($_POST['form_id']))
			{
				return null;
			}
			if (\CModule::IncludeModule('xpage_taskmgr'))
			{
				$form_id = $_POST['form_id'];
				switch ($form_id)
				{
					case 'form_addtask':
						$arFields = [
							'NAME'                => $_POST['name'],
							'ACTIVE'              => $_POST['active'],
							'PRIORITY'            => $_POST['priority'],
							'TIME_START'          => $_POST['time_start'],
							'TASK_INTERVAL'       => (int)$_POST['interval'],
							'COMMAND'             => $_POST['command'],
							'ENABLE_SMS'          => $_POST['enable_sms'],
							'ENABLE_MAIL'         => $_POST['enable_mail'],
							'SMS_MESSAGE_OK'      => $_POST['SMS_MESSAGE_OK'],
							'SMS_MESSAGE_ERROR'   => $_POST['SMS_MESSAGE_ERROR'],
							'EMAIL_MESSAGE_OK'    => $_POST['EMAIL_MESSAGE_OK'],
							'EMAIL_MESSAGE_ERROR' => $_POST['EMAIL_MESSAGE_ERROR'],
							'SMS_PHONES'          => $_POST['sms_phones'],
							'EMAIL_ADDESSES'      => $_POST['email_addresses'],
							'NOTIFICATIONS_COUNT' => (int)$_POST['NOTIFICATIONS_COUNT'],
							'TASK_GROUP'          => $_POST['TASK_GROUP'],
							'AUTORUN'             => $_POST['AUTORUN'],
						];
						\Xpage\Taskmgr\TaskTable::add($arFields);
						break;

					case 'form_edittask':
						$taskID = $_REQUEST['TASK_ID'];
						if ($taskID)
						{
							if (isset($_POST['delete']))
							{
								\Xpage\Taskmgr\TaskTable::delete($taskID);
							}
							else
							{
								$arFields = [
									'NAME'                => $_POST['name'],
									'ACTIVE'              => $_POST['active'],
									'PRIORITY'            => $_POST['priority'],
									'TIME_START'          => $_POST['time_start'],
									'TASK_INTERVAL'       => (int)$_POST['interval'],
									'COMMAND'             => $_POST['command'],
									'ENABLE_SMS'          => $_POST['enable_sms'],
									'ENABLE_MAIL'         => $_POST['enable_mail'],
									'SMS_MESSAGE_OK'      => $_POST['SMS_MESSAGE_OK'],
									'SMS_MESSAGE_ERROR'   => $_POST['SMS_MESSAGE_ERROR'],
									'EMAIL_MESSAGE_OK'    => $_POST['EMAIL_MESSAGE_OK'],
									'EMAIL_MESSAGE_ERROR' => $_POST['EMAIL_MESSAGE_ERROR'],
									'SMS_PHONES'          => $_POST['sms_phones'],
									'EMAIL_ADDESSES'      => $_POST['email_addresses'],
									'NOTIFICATIONS_COUNT' => (int)$_POST['NOTIFICATIONS_COUNT'],
									'TASK_GROUP'          => $_POST['TASK_GROUP'],
									'AUTORUN'             => $_POST['AUTORUN'],
								];
								\Xpage\Taskmgr\TaskTable::update($taskID, $arFields);
							}
							LocalRedirect($APPLICATION->GetCurPageParam('action=tasks', ['action', 'TASK_ID']));
						}
						break;

					default:

						break;
				}
			}

		}

	}
