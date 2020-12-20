<?php

declare(strict_types=1);


final class IntIntMapFactory implements IntIntMapFactoryInterface
{
    public function create(int $size): IntIntMapInterface
    {
      return new IntIntMap(

      );
    }
}