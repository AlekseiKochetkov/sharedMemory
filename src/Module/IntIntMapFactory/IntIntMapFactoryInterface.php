<?php

declare(strict_types=1);

interface IntIntMapFactoryInterface
{
    public function create(int $size): IntIntMapInterface;
}