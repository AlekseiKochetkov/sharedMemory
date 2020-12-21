<?php

declare(strict_types=1);

namespace App\Module\IntIntMap;

use Exception;

final class IntIntMapV3 implements IntIntMapInterface
{
    private const SIZE = 10;
    /**
     * @var resource
     */
    private $shm_id;

    private int $size;

    private int $keysPartSize;

    public function __construct($shm_id, int $size)
    {
        $this->shm_id = $shm_id;
        $this->size = $size;
        $this->keysPartSize = (int)round($size / 2);

        //store everything inside of object??
    }

    //how to get rid of parsing
    public function put(int $key, int $value): ?int
    {
        //get part of storage to store keys and offsets
        //always parse keys and offsets
        //get value by offset

        $lastMap = $this->getLastMap();
        $map = $this->getMap($key, $value);
        $preparedString = $this->prepareSingleKeyValuePair($map->getIndexKey(), $map->getValue());
        if (strlen($preparedString) > $this->size) {
            throw new Exception('Out of memory. Unable to write');
        }
        shmop_write($this->shm_id, $preparedString, $map->getOffset());

        return $lastMap->getValue();//??
        // TODO: Implement put() method.
    }

    public function get(int $key): ?int
    {
        return $this->getExistingMap($key)->getValue();

    }

    private function getMap(int $key, int $value): SomeMap
    {
        return (new SomeMap())
            ->setIndexKey($key)
            ->setValue($value)
            ->setOffset($this->getOffset($key));
    }

    private function getOffset(int $key): int
    {
        //get current offset
    }

    private function prepareSingleKeyValuePair(int $key, ?int $value): string
    {
        return $this->createString($key).':'.$this->createString($value);
    }

    private function createString(int $value): string
    {
        return str_pad((string)$value, self::SIZE, ' ');
    }

    private function getLastMap(): SomeMap
    {

    }

    private function getExistingMap(int $key): SomeMap
    {
        $offset = $this->getOffsetForKey($key);

        return (new SomeMap())
            ->setValue(
                (int)shmop_read($this->shm_id, $offset, self::SIZE)
            );
    }

    private function getOffsetForKey(int $key): int
    {
        $wholeData = shmop_read($this->shm_id, 0, $this->keysPartSize);
        $substring = explode(':', substr($wholeData, strpos($wholeData, (string)$key), 2*self::SIZE + 1));
        return (int)$substring[1];
    }
}