<?php

declare(strict_types=1);

use App\Module\IntIntMap\IntIntMap;
use App\Module\IntIntMap\IntIntMapInterface;
use PHPUnit\Framework\TestCase;

final class IntIntMapTest extends TestCase
{
    private const SIZE = 50;
    private const KEY = 5;
    private const VALUE = 7;
    private const SHMOP_KEY = 8;

    /**
     * @var resource|null
     */
    private $shmop = null;

    /**
     * @test
     */
    public function put(): void
    {
        $instance = $this->createInstance();

        $result = $instance->put(self::KEY, self::VALUE);

        self::assertNull($result);
    }

    /**
     * @test
     * @depends put
     */
    public function get(): void
    {
        $instance = $this->createInstance();

        $result = $instance->get(self::KEY);

        self::assertEquals(self::VALUE, $result);
    }

    /**
     * @test
     * @depends put
     */
    public function putAnother(): void
    {
        $instance = $this->createInstance();

        $result = $instance->put(self::KEY, self::VALUE);

        self::assertEquals(self::VALUE, $result);
    }

    /**
     * @test
     * @depends put
     * @depends get
     * @depends putAnother
     * @doesNotPerformAssertions
     */
    public function customTearDown(): void
    {
        shmop_delete($this->createShmop());
    }

    private function createInstance(): IntIntMapInterface
    {
        return new IntIntMap(
            $this->createShmop(),
            self::SIZE
        );
    }

    /**
     * @return resource
     */
    private function createShmop()
    {
        if (null === $this->shmop) {
            $this->shmop = shmop_open(
                self::SHMOP_KEY,
                'c',
                0644,
                self::SIZE
            );
        }

        return $this->shmop;
    }
}
