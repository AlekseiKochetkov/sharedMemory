<?php

declare(strict_types=1);

namespace App\Module\IntIntMapFactory;

use App\Module\IntIntMap\IntIntMap;
use App\Module\IntIntMap\IntIntMapInterface;

final class IntIntMapFactory implements IntIntMapFactoryInterface
{
    public function create(int $size): IntIntMapInterface
    {
        return new IntIntMap(
            shmop_open($this->createKey(), "c", 0600, $size),
            $size
        );
    }

    private function createKey(): int
    {
        return 0;
    }


}