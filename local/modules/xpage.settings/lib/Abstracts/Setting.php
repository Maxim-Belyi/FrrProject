<?php
declare(strict_types=1);

namespace Xpage\Settings\Abstracts;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use RuntimeException;
use Xpage\Settings\Collections\SettingCollection;
use Xpage\Settings\EntityTables\SettingsTable;
use Xpage\Settings\Models\Fields\SettingFile;
use Xpage\Settings\Models\Fields\SettingTypeInterface;
use Xpage\Settings\Models\SettingFieldType;

abstract class Setting extends ModelAbstract
{
	public const cacheKeyMask = "settings_%s_%s";
	public const cacheDir = 'xpage.settings';

	/**
	 * @return SettingCollection
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function getAll(): SettingCollection
	{
		$items = self::getTableEntity()::getList([
			'select' => ['*', 'FIELD'],
		])->fetchCollection();
		$collection = new SettingCollection();
		if (!is_null($items))
		{
			$collection->bindOrmCollection($items);
		}

		return $collection;
	}

	/**
	 * @return DataManager
	 */
	public static function getTableEntity(): string
	{
		return SettingsTable::class;
	}

	/**
	 * @param string $code
	 *
	 * @return SettingFile|null
	 */
	public static function getFile(string $code): ?int
	{
		$item = self::getByCodeAndType($code, 'file');
		if (!is_null($item))
		{
			return (int)$item->getValue();
		}

		return null;
	}

	/**
	 * @param string $code
	 * @param string $type
	 *
	 * @return null|SettingTypeInterface
	 */
	private static function getByCodeAndType(string $code, string $type): ?SettingTypeInterface
	{
		$cache = Cache::createInstance();
		$cacheKey = self::getCacheKey($code, $type);
		$result = null;
		if ($cache->initCache(86400, $cacheKey, 'xpage.settings'))
		{
			$result = unserialize($cache->getVars());
		}
		else if ($cache->startDataCache())
		{
			$item = self::getTableEntity()::getList([
				'select' => [
					'*', 'FIELD',
				],
				'filter' => [
					'=code'       => $code,
					'=FIELD.code' => $type,
				],
			])->fetchObject();

			if (is_null($item))
			{
				$cache->abortDataCache();

				return null;
			}

			$handler = $item->get('FIELD')->get('field_handler');
			$result = new $handler($item);
			$cache->endDataCache(serialize($result));
		}

		return $result;
	}

	public static function getCacheKey(string $code, string $type): string
	{
		return sprintf(self::cacheKeyMask, $code, $type);
	}

	/**
	 * @param string $code
	 *
	 * @return float|null
	 */
	public static function getFloat(string $code): ?float
	{
		$item = self::getByCodeAndType($code, 'float');
		if (!is_null($item))
		{
			return $item->getValue();
		}

		return null;
	}

	public static function getByCode(string $code): ?SettingTypeInterface
	{
		$item = self::getTableEntity()::getList([
			'select' => [
				'*', 'FIELD',
			],
			'filter' => [
				'=code' => $code,
			],
		])->fetchObject();

		if (is_null($item))
		{
			return null;
		}
		$handler = $item->get('FIELD')->get('field_handler');

		return new $handler($item);
	}

	/**
	 * @param string $code
	 *
	 * @return int|null
	 */
	public static function getInt(string $code): ?int
	{
		$item = self::getByCodeAndType($code, 'integer');
		if (!is_null($item))
		{
			return $item->getValue();
		}

		return null;
	}

	/**
	 * @param string $code
	 *
	 * @return null|bool
	 */
	public static function getBool(string $code): ?bool
	{
		$item = self::getByCodeAndType($code, 'bool');
		if (!is_null($item))
		{
			return $item->getValue();
		}

		return null;
	}

	/**
	 * @param string $code
	 *
	 * @return null|string
	 */
	public static function getString(string $code): ?string
	{
		$item = self::getByCodeAndType($code, 'string');
		if (!is_null($item))
		{
			return $item->getValue();
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->get('name');
	}

	/**
	 * @param $value
	 *
	 * @return void
	 */
	public function setValue($value): void
	{
		$this->clearCache();
		$this->set('value', $value)->save();
	}

	public function clearCache(): void
	{
		$cache = Cache::createInstance();
		$cache->clean(self::getCacheKey($this->getCode(), $this->getField()->getCode()), self::cacheDir);
	}

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->get('code');
	}

	/**
	 * @return SettingFieldType
	 */
	public function getField(): SettingFieldType
	{
		return new SettingFieldType($this->get('FIELD'));
	}
}