<?php

use MetaRush\DataMapper;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    private $dbFile;

    public function setUp()
    {
        $this->dbFile = __DIR__ . '/test.db';
    }

    public function testBuilder()
    {
        $builder = (new DataMapper\Builder)
            ->setDsn('sqlite:' . $this->dbFile);

        $mapper = $builder->build();

        $this->assertInstanceOf(DataMapper\DataMapper::class, $mapper);
    }

    public function tearDown()
    {
        if (file_exists($this->dbFile))
            unlink($this->dbFile);
    }
}
