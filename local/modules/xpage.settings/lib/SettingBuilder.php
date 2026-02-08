<?php
declare(strict_types=1);

namespace Xpage\Settings;

use CFile;
use RuntimeException;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\SettingFieldType;

class SettingBuilder
{
	private ?string $code = null;
	private ?string $name = null;
	private ?string $value = null;
	private ?SettingFieldType $type = null;
	private array $requiredFields = [
		'field_type',
		'code',
		'name',
	];

	public function __construct() {}

	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	public function setFieldType(SettingFieldType $type): self
	{

		$this->type = $type;

		return $this;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function setValue($value): self
	{
		$this->value = $value;

		return $this;
	}

	public function save(): void
	{
		$params = [
			'field_type' => $this->type->getId(),
			'code'       => $this->code,
			'value'      => $this->value,
			'name'       => $this->name,
		];

		if (Setting::getByCode($params['code']))
		{
			throw new RuntimeException("Параметр с кодом {$params['code']} уже существует");
		}

		foreach ($this->requiredFields as $requiredField)
		{
			if (!array_key_exists($requiredField, $params))
			{
				throw new RuntimeException("$requiredField обязательно для заполнения");
			}
			if (empty($params[$requiredField]))
			{
				throw new RuntimeException("$requiredField обязательно для заполнения");
			}
		}

		switch ($params['field_type'])
		{
			case 'integer':
				$params['value'] = (int)$params['value'];
				break;
			case 'string':
				$params['value'] = (string)$params['value'];
				break;
			case 'float':
				$params['value'] = (float)$params['value'];
				break;
			case 'bool':
				$params['value'] = (bool)$params['value'];
				break;
			case 'file':
				if ((int)$params['value'] > 0 && CFile::GetByID((int)$params['field_type']))
				{
					$params['value'] = (int)$params['value'];
				}
				elseif (is_array($params['value']) && $params['value']['tmp_name'])
				{
					$params['value'] = CFile::SaveFile($params['value'], 'modules/xpage.settings');
				}
				break;
		}

		$result = Setting::getTableEntity()::add($params);
		if (!$result->isSuccess())
		{
			throw new RuntimeException(implode('; ', $result->getErrorMessages()));
		}

	}
}