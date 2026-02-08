<?php
declare(strict_types=1);

namespace Xpage\Settings\Models;

use Bitrix\Main\Entity\DataManager;
use Xpage\Settings\Abstracts\ModelAbstract;
use Xpage\Settings\EntityTables\SettingFieldsTable;

class SettingFieldType extends ModelAbstract
{

	public static function getByCode(string $code): ?SettingFieldType
	{
		$item = self::getTableEntity()::getList([
			'select' => ['*'],
			'filter' => ['=code' => $code],
		])->fetchObject();

		if(is_null($item))
		{
			return null;
		}

		return new self($item);
	}

	/**
	 * @return DataManager
	 */
	public static function getTableEntity(): string
	{
		return SettingFieldsTable::class;
	}

	public function getCode(): string
	{
		return $this->get('code');
	}
}