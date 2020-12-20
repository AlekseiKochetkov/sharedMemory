<?php

declare(strict_types=1);

namespace App\Module\IntIntMapFactory;

use App\Module\IntIntMap\IntIntMapInterface;

interface IntIntMapFactoryInterface
{
    public function create(int $size): IntIntMapInterface;
}