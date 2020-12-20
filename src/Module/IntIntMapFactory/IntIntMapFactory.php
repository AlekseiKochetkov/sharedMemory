<?php

declare(strict_types=1);


final class IntIntMapFactory implements IntIntMapFactoryInterface
{
    public function create(int $size): IntIntMapInterface
    {
        return new IntIntMap(
            shmop_open($this->createKey(), "w", 0600, $size),
            $size
        );
    }

    private function createKey(): int
    {
        return 0;
    }


}