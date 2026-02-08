<?php
declare(strict_types=1);

namespace Xpage\Settings\EntityTables;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Objectify\Collection;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);


class SettingsTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName(): string
	{
		return 'xpage_settings';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 * @throws SystemException
	 * @throws SystemException
	 */
	public static function getMap(): array
	{
		return [
			new IntegerField(
				'id',
				[
					'primary'      => true,
					'autocomplete' => true,
					'title'        => Loc::getMessage('PARAMS_ENTITY_ID_FIELD'),
				]
			),
			new IntegerField(
				'field_type',
				[
					'required' => true,
					'title'    => Loc::getMessage('PARAMS_ENTITY_FIELD_TYPE_FIELD'),
				]
			),
			new StringField(
				'code',
				[
					'required'   => true,
					'validation' => [__CLASS__, 'validateCode'],
					'title'      => Loc::getMessage('PARAMS_ENTITY_CODE_FIELD'),
				]
			),
			new StringField(
				'name',
				[
					'required'   => true,
					'validation' => [__CLASS__, 'validateName'],
					'title'      => Loc::getMessage('PARAMS_ENTITY_NAME_FIELD'),
				]
			),
			(new StringField(
				'value',
				[
					'required'   => false,
					'validation' => [__CLASS__, 'validateCode'],
					'title'      => Loc::getMessage('PARAMS_ENTITY_CODE_FIELD'),
				]
			))->configureNullable(),
			new IntegerField(
				'sort',
				[
					'default' => 100,
					'title'   => Loc::getMessage('SETTINGS_ENTITY_SORT_FIELD'),
				]
			),

			new ReferenceField(
				'FIELD',
				SettingFieldsTable::class,
				Join::on('this.field_type', '=', 'ref.id'),
			),
		];
	}

	/**
	 * Returns validators for code field.
	 *
	 * @return array
	 * @throws ArgumentTypeException
	 * @throws ArgumentTypeException
	 */
	public static function validateCode(): array
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	/**
	 * Returns validators for name field.
	 *
	 * @return array
	 * @throws ArgumentTypeException
	 */
	public static function validateName(): array
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	public static function getAll(): ?Collection
	{
		$items = self::getList([
			'select' => [
				'*', 'FIELD',
			],
			'order'  => ['sort' => 'DESC'],
		])->fetchCollection();

		return $items;
	}
}