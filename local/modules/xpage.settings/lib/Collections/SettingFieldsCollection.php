<?php

namespace Xpage\Settings\Collections;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Objectify\Collection;
use Xpage\Settings\Abstracts\CollectionAbstract;
use Xpage\Settings\Models\SettingFieldType;

class SettingFieldsCollection extends CollectionAbstract
{
	/**
	 * @throws ArgumentException
	 */
	public function bindOrmCollection(Collection $collection): void
	{
		foreach ($collection as $item)
		{
			$this->push(new SettingFieldType($item));
		}
	}

	/**
	 * @throws ArgumentException
	 */
	public function push($entity): void
	{
		if ($entity instanceof SettingFieldType)
		{
			$this->items[] = $entity;
		}
		else
		{
			throw new ArgumentException("Коллекция может содержать только экземпляры \Xpage\Settings\Models\SettingFieldType");
		}
	}
}