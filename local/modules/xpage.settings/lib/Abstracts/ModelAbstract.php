<?php

namespace Xpage\Settings\Abstracts;

use Bitrix\Main\ORM\Objectify\EntityObject;

abstract class ModelAbstract
{
    protected EntityObject $data;

    public function __construct(EntityObject $data)
    {
        $this->data = $data;
    }

    abstract static function getTableEntity();

    public function __get(string $name)
    {
        return $this->getData()->get($name);
    }

    public function addTo(string $name, EntityObject $value): self
    {
        $this->getData()->addTo($name, $value);

        return $this;
    }

    public function delete(): void
    {
        $this->getData()->delete();
    }

    public function get(string $fieldName)
    {
        return $this->getData()->get($fieldName);
    }

    public function getId(): int
    {
        return $this->getData()->getId();
    }

    public function save(): void
    {
        $this->getData()->save();
    }

    public function set(string $name, $value): self
    {
        $this->getData()->set($name, $value);

        return $this;
    }

    public function has(string $field): bool
    {
        return $this->getData()->has($field);
    }

    public function isFilled(string $field): bool
    {
        return $this->getData()->isFilled($field);
    }

    /**
     * @return EntityObject
     */
    protected function getData(): EntityObject
    {
        return $this->data;
    }
}