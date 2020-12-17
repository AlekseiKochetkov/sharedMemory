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
     * @return int|null предыдущее значение // что значит предыдущее значение?
     */
    public function put(int $key, int $value): ?int
    {
        //v1
        //проверять что осталось место в выделенной памяти
//        shmop_write($this->shm_id, $value, $this->getSharedMemoryPlace($key));
        // надо сохранить пару  ключ\значение
        // по ключу надо определять место в памяти куда записано
        // где хранить ссылку ключа на память?
        // получить и вернуть предыдущее значение

        //v2
        //считать всё
        //получить длину записанного
        //  заодно получить последний кусок
        //дописать в конец

        $rawData = shmop_read($this->shm_id, 0, $this->size);
        shmop_write($this->shm_id, $this->prepareValue($value), strlen($rawData));

        return $value;
    }

    /**
     * @param int $key ключ
     * @return int|null значение сохраненное ранее по этому ключу
     */
    public function get(int $key): ?int
    {
        //v1
        //по интовому ключу определить место в памяти
        //узнать длину числа, т.к. в памяти хранятся только строчные данные
        //прочитать из памяти, изи

        //v2
        //считать всё
        // распарсить
        //получить нужное
        $parsedData = $this->parse(
            shmop_read($this->shm_id, 0, $this->size)
        );
        if (
            null !== $parsedData
            && array_key_exists($key, $parsedData)
        ) {
            return $parsedData[$key];
        }

        return null;
    }

    private function parse(string $wholeData): array
    {
        return array_map(
            [$this, 'createKeyValuePair'],
            explode(';', $wholeData)
        );
    }

    private function createKeyValuePair(string $chunk): ?array
    {
        $subStrings = explode(':', $chunk);
        if (count($subStrings) === 2) {
            return [$subStrings[0] => $subStrings[1]];
        }

        return null;
    }

    private function prepareValue(int $key, int $value): string
    {
        return $key.':'.$value;
    }
}
