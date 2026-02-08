<?php
declare(strict_types=1);

namespace Xpage\Settings\EntityTables;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class SettingFieldsTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName(): string
	{
		return 'xpage_setting_fields';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
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
					'title'        => Loc::getMessage('FIELDS_ENTITY_ID_FIELD'),
				]
			),
			new StringField(
				'name',
				[
					'required'   => true,
					'validation' => [__CLASS__, 'validateName'],
					'title'      => Loc::getMessage('FIELDS_ENTITY_NAME_FIELD'),
				]
			),
			new StringField(
				'code',
				[
					'required'   => true,
					'validation' => [__CLASS__, 'validateName'],
					'title'      => Loc::getMessage('FIELDS_ENTITY_NAME_FIELD'),
				]
			),
			new StringField(
				'field_handler',
				[
					'required'   => true,
					'validation' => [__CLASS__, 'validateName'],
					'title'      => Loc::getMessage('FIELDS_ENTITY_NAME_FIELD'),
				]
			),
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
}