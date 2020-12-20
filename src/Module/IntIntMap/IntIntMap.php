<?php

namespace App\Module\IntIntMap;

use Exception;

/**
 * Требуется написать IntIntMap который по произвольному int ключу хранить произвольное int значение
 * Все данные требуется хранить в выделенном заранее блоке в разделяемой памяти
 * для доступа к памяти напрямую необходимо (и достаточно) использовать следующие два метода:
 * \shmop_read и \shmop_write
 */
class IntIntMap implements IntIntMapInterface
{
    /**
     * @var resource
     */
    private $shm_id;

    private int $size;

    /**
     * IntIntMap constructor.
     * @param resource $shm_id результат вызова \shmop_open
     * @param int $size размер зарезервированного блока в разделяемой памяти
     */
    public function __construct($shm_id, int $size)
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
        $parsedData = $this->getParsedData();
        $lastValue = $parsedData[array_key_last($parsedData)];
        $parsedData[$key] = $value;
        $preparedString = $this->prepareValues($parsedData);
        if (strlen($preparedString) > $this->size) {
            throw new Exception('Out of memory. Unable to write');
        }
        shmop_write($this->shm_id, $preparedString, 0);

        return $lastValue;//??
    }

    /**
     * @param int $key ключ
     * @return int|null значение сохраненное ранее по этому ключу
     */
    public function get(int $key): ?int
    {
        $parsedData = $this->getParsedData();
        if (
            null !== $parsedData
            && array_key_exists($key, $parsedData)
        ) {
            return $parsedData[$key];
        }

        return null;
    }

    private function getParsedData(): array
    {
        $parsedData = $this->parse(shmop_read($this->shm_id, 0, $this->size));
        if (null != $parsedData && count($parsedData) > 1) {
            return array_replace(...$parsedData);
        }

        return $parsedData;
    }

    private function parse(string $wholeData): array
    {
        return array_map(
            [$this, 'parseKeyValuePair'],
            explode(';', $wholeData)
        );
    }

    private function parseKeyValuePair(string $chunk): ?array
    {
        $subStrings = explode(':', $chunk);
        if (count($subStrings) === 2) {
            return [(int)$subStrings[0] => (int)$subStrings[1]];
        }

        return null;
    }

    private function prepareValues(array $parsedData): string
    {
        return implode(
            ';',
            array_map(
                [$this, 'prepareSingleKeyValuePair'],
                array_keys($parsedData),
                $parsedData
            )
        );
    }

    private function prepareSingleKeyValuePair(int $key, ?int $value): string
    {
        return $key.':'.$value;
    }

    public function __destruct()
    {
        shmop_close($this->shm_id);
    }
}
