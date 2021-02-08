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
            ->setAdapter('AtlasQuery') // not needed but included for unit test code coverage only
            ->setDbUser('')
            ->setDbPass('')
            ->setDsn('sqlite:' . $this->dbFile);

        $mapper = $builder->build();

        $this->assertInstanceOf(DataMapper\DataMapper::class, $mapper);
    }

    public function tearDown()
    {
        if (\file_exists($this->dbFile))
            \unlink($this->dbFile);
    }

    /**
     * Real intention of this test is for test coverage of our try/catch block in AtlasQuery adapter
     */
    public function testWrongDbDriver()
    {
        $this->expectExceptionMessageRegExp('/could not find driver/');

        (new DataMapper\Builder)
            ->setDsn('wrongDbDriver:')
            ->build();
    }

}