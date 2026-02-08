<?php
declare(strict_types=1);

namespace Xpage\Settings\Models\Fields;

use CAdminForm;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\SettingFieldType;

class SettingFloat extends Setting implements SettingTypeInterface
{
	public static function add(array $params): void
	{
		self::getTableEntity()::add(array_merge(['field_type' => SettingFieldType::getByCode('float')->getId()], $params));
	}

	public function draw(CAdminForm $form): void
	{
		$form->AddEditField(
			$this->getCode(),
			$this->getName() . " ({$this->getCode()})",
			false, [
			'maxlength' => 255,
			'size'      => 10,
		], $this->getValue());
	}

	/**
	 * @return float
	 */
	public function getValue(): ?float
	{
		if (!is_null($this->get('value')))
		{
			return (float)$this->get('value');
		}

		return null;
	}
}