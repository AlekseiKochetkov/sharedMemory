<?php

declare(strict_types=1);


interface IntIntMapInterface
{
    public function put(int $key, int $value): ?int;

    public function get(int $key): ?int;
}