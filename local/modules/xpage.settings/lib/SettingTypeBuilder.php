<?php
declare(strict_types=1);

namespace Xpage\Settings;

use RuntimeException;
use Xpage\Settings\Models\SettingFieldType;

class SettingTypeBuilder
{
	/**
	 * @var string
	 */
	private string $code = '';
	/**
	 * @var string
	 */
	private string $name = '';
	/**
	 * @var string
	 */
	private string $handler = '';

	private array $requiredFields = [
		'code', 'field_handler',
	];

	public function __construct() {}

	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function setHandler(string $classType): self
	{
		if (!class_exists($classType))
		{
			throw new RuntimeException('Класс ' . $classType . ' не найден');
		}

		$this->handler = $classType;

		return $this;
	}

	public function save(): void
	{
		$params = [
			'code'          => $this->code,
			'name'          => $this->name,
			'field_handler' => $this->handler,
		];

		if(SettingFieldType::getByCode($params['code']))
		{
			throw new RuntimeException("Тип поля с кодом {$params['code']} уже существует");
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

		$result = SettingFieldType::getTableEntity()::add($params);
		if (!$result->isSuccess())
		{
			throw new RuntimeException(implode('; ', $result->getErrorMessages()));
		}

	}
}