<?php

namespace Xpage\Settings\Abstracts;

use Iterator;
use Countable;

abstract class CollectionAbstract implements Iterator, Countable
{
    protected array $items = [];

    public function count(): int
    {
        return count($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    /**
     * @param callable $function
     *
     * @return void
     */
    public function each(callable $function): void
    {
        foreach ($this->items as $key => $item)
        {
            $function($item, $key, $this->items);
        }
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function extractById(int $id): void
    {
        foreach ($this->items as $index => $item)
        {
            if ( $item->getId() === $id )
            {
                unset($this->items[$index]);
                break;
            }
        }
    }

    public function key()
    {
        return key($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    /**
     * @param $entity
     *
     * @return void
     */
    abstract public function push($entity): void;

    public function rewind(): void
    {
        reset($this->items);
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }
}