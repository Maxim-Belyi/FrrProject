<?php

namespace Xpage\Settings\Collections;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Objectify\Collection;
use Xpage\Settings\Abstracts\CollectionAbstract;
use Xpage\Settings\Models\Fields\SettingTypeInterface;


class SettingCollection extends CollectionAbstract
{
    /**
     * @throws ArgumentException
     */
    public function push($entity): void
    {
        if ( $entity instanceof SettingTypeInterface )
        {
            $this->items[] = $entity;
        }
        else
        {
            throw new ArgumentException("Коллекция может содержать только экземпляры \Xpage\Settings\Models\Fields\SettingTypeInterface");
        }
    }

    /**
     * @throws ArgumentException
     */
    public function bindOrmCollection(Collection $collection): void
    {
        foreach ($collection as $item)
        {
			$handler = $item->get('FIELD')->get('field_handler');
			$this->push(new $handler($item));
        }
    }
}