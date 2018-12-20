<?php

use MetaRush\DataMapper\Adapters\AtlasQuery;
use MetaRush\DataMapper\DataMapper;
use PHPUnit\Framework\TestCase;

class AtlasQueryAdapterTest extends TestCase
{
    private $dataMapper;

    public function setUp()
    {
        $dsn = 'sqlite:' . __DIR__ . '/test.db';
        $adapter = new AtlasQuery($dsn, null, null);
        $this->dataMapper = new DataMapper($adapter);
    }

    public function testCreateNewRecord()
    {
        $result = $this->dataMapper->create('Users', ['firstName' => 'Meta', 'lastName' => 'Rush']);

        $this->assertInternalType('integer', $result);
    }
}
