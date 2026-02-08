<?php
declare(strict_types=1);

namespace Xpage\Settings\Models\Fields;

use CAdminForm;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\SettingFieldType;

class SettingString extends Setting implements SettingTypeInterface
{
	public static function add(array $params): void
	{
		self::getTableEntity()::add(array_merge(['field_type' => SettingFieldType::getByCode('string')->getId()], $params));
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return (string)$this->get('value');
	}

	public function draw(CAdminForm $form): void
	{
		$form->AddEditField(
			$this->getCode(),
			$this->getName(). " ({$this->getCode()})",
			false, [
			'maxlength' => 255
		], $this->getValue());
	}
}