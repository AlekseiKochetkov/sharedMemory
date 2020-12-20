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
    private const SHMOP_KEY = 1;

    /**
     * @var resource|null
     */
    private $shmop = null;

    /**
     * @test
     */
    public function get(): void
    {
        $instance = $this->createInstance();

        $instance->put(self::KEY, self::VALUE);
        $result = $instance->get(self::KEY);

        self::assertEquals(self::VALUE, $result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
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
        return $this->shmop ?? shmop_open(
                self::SHMOP_KEY,
                'c',
                0644,
                self::SIZE
            );
    }
}
