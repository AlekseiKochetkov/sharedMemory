<?php


/**
 * Требуется написать IntIntMap который по произвольному int ключу хранить произвольное int значение
 * Все данные требуется хранить в выделенном заранее блоке в разделяемой памяти
 * для доступа к памяти напрямую необходимо (и достаточно) использовать следующие два метода:
 * \shmop_read и \shmop_write
 */
class IntIntMap
{
    private resource $shm_id;

    private int $size;
    /**
     * IntIntMap constructor.
     * @param resource $shm_id результат вызова \shmop_open
     * @param int $size размер зарезервированного блока в разделяемой памяти
     */
    public function __construct(resource $shm_id, int $size)
    {
        $this->shm_id = $shm_id;
        $this->size = $size;
    }

    /**
     * @param int $key произвольный ключ
     * @param int $value произвольное значение
     * @return int|null предыдущее значение
     */
    public function put(int $key, int $value): ?int
    {
        // надо сохранить пару  ключ\значение
        // по ключу надо определять место в памяти куда записано
        // где хранить ссылку ключа на память?
    }

    /**
     * @param int $key ключ
     * @return int|null значение сохраненное ранее по этому ключу
     */
    public function get(int $key): ?int
    {
        //по интовому ключу определить место в памяти
        //прочитать из памяти, изи
    }

    //нужна функция для превращения ключа в место в памяти - как
    private function getSharedMemoryPlace(int $key): int //??
    {
        return $key;
    }
}
