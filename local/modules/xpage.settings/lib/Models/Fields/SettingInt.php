<?php
declare(strict_types=1);

namespace Xpage\Settings\Models\Fields;

use CAdminForm;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\SettingFieldType;

class SettingInt extends Setting implements SettingTypeInterface
{
	public static function add(array $params): void
	{
		self::getTableEntity()::add(array_merge(['field_type' => SettingFieldType::getByCode('integer')->getId()], $params));
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
	 * @return int
	 */
	public function getValue(): ?int
	{
		if (!is_null($this->get('value')))
		{
			return (int)$this->get('value');
		}

		return null;
	}
}