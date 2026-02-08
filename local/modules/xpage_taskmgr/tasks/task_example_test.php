<?php

	class TaskExampleTest extends \Xpage\Taskmgr\TaskBase
	{
		public function runTask()
		{
			// TODO: Implement runTask() method.
		}

		public static function getTaskName(): string
		{
			return 'Тестовая задача для примера';

		}

		// $this->getLastSuccessDate() дата последнего успешного запуска
		public function runCondition(): bool
		{
			return false;
		}
	}