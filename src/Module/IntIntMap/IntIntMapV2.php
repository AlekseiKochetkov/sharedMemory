<?php

declare(strict_types=1);


final class IntIntMapV2 implements IntIntMapInterface
{
    private resource $shm_id;

    private int $size;

    public function __construct($shm_id, int $size)
    {
        $this->shm_id = $shm_id;
        $this->size = $size;
    }


    public function put(int $key, int $value): ?int
    {
        //проверять что осталось место в выделенной памяти
        //        shmop_write($this->shm_id, $value, $this->getSharedMemoryPlace($key));
        // надо сохранить пару  ключ\значение
        // по ключу надо определять место в памяти куда записано
        // где хранить ссылку ключа на память?
        // получить и вернуть предыдущее значение
    }

    public function get(int $key): ?int
    {
        //по интовому ключу определить место в памяти
        //узнать длину числа, т.к. в памяти хранятся только строчные данные
        //прочитать из памяти, изи
    }

}