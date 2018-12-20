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

    public function testCreate()
    {
        $insertId = $this->dataMapper->create('Users', ['firstName' => 'Meta', 'lastName' => 'Rush']);

        $this->assertInternalType('integer', $insertId);
    }

    public function testFindOne()
    {
        $row = $this->dataMapper->findOne('Users', ['firstName' => 'Meta']);

        $this->assertInternalType('array', $row);

        $this->assertEquals('Meta', $row['firstName']);
    }
}
