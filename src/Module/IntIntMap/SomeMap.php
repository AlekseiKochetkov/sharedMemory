<?php

declare(strict_types=1);

namespace App\Module\IntIntMap;

final class SomeMap
{
    private int $indexKey;

    private int $value;

    private int $offset;

    public function getIndexKey(): int
    {
        return $this->indexKey;
    }

    public function setIndexKey(int $indexKey): SomeMap
    {
        $this->indexKey = $indexKey;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): SomeMap
    {
        $this->value = $value;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): SomeMap
    {
        $this->offset = $offset;

        return $this;
    }
}