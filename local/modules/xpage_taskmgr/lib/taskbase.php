<?php

	namespace Xpage\Taskmgr;

	abstract class TaskBase
	{
		/**
		 * @var \Xpage\Taskmgr\TaskMgr
		 */
		public  $taskMgr;
		private $id;

		abstract function runTask();

		abstract static function getTaskName(): string;

		public function __construct()
		{
		}

		public function setId(?int $id)
		{
			$this->id = $id;
		}

		public function getLastSuccessDate(): ?\Bitrix\Main\Type\DateTime
		{
			$data = \Xpage\Taskmgr\LogTable::getRow([
														'select' => [
															'DATETIME_START',
															'DATETIME_END',
														],
														'filter' => [
															'=TASK_ID' => $this->taskMgr->currentTaskData['ID'],
															'=STATUS'  => 'success',
														],
														'order'  => [
															'ID' => 'DESC',
														],
													]);
			return $data['DATETIME_END'] ?: null;
		}

		//проверка была ли выполнена задача за последние сколько-то секунд
		public function checkFrequencyLimit(int $seconds): bool
		{
			$lastSuccessDate = $this->getLastSuccessDate();

			if (!$lastSuccessDate)
			{
				return false;
			}
			$secondsSinceLastSuccess = time() - $lastSuccessDate->getTimestamp();
			return $secondsSinceLastSuccess < $seconds;
		}

		public function setTaskMgr(&$taskMgr)
		{
			$this->taskMgr = $taskMgr;
		}

		public function run()
		{
			$this->runTask();
		}

		public function getInterval(): ?int
		{
			return $this->taskMgr->currentTaskData['TASK_INTERVAL'];
		}

		/**
		 * @return mixed
		 */
		public function getId(): ?int
		{
			return $this->id ?? null;
		}
	}

