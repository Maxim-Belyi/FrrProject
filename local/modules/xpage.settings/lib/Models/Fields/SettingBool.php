<?php
declare(strict_types=1);

namespace Xpage\Settings\Models\Fields;

use CAdminForm;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\SettingFieldType;

class SettingBool extends Setting implements SettingTypeInterface
{
	public static function add(array $params): void
	{
		self::getTableEntity()::add(array_merge(['field_type' => SettingFieldType::getByCode('bool')->getId()], $params));
	}

	public function draw(CAdminForm $form): void
	{
		$form->AddCheckBoxField(
			$this->getCode(),
			$this->getName() . " ({$this->getCode()})",
			false,
			$this->getValue(),
			$this->getValue()
		);
	}

	/**
	 * @return bool
	 */
	public function getValue(): bool
	{
		return (bool)$this->get('value');
	}
}