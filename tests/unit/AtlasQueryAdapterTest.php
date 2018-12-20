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
        //$insertId = $this->dataMapper->create('Users', ['firstName' => 'Foo', 'lastName' => 'Bar']);

        $this->assertInternalType('integer', $insertId);
    }

    public function testFindOne()
    {
        $row = $this->dataMapper->findOne('Users', ['firstName' => 'Meta']);

        $this->assertInternalType('array', $row);

        $this->assertEquals('Meta', $row['firstName']);
    }

    public function testFindAllWithWhere()
    {
        $rows = $this->dataMapper->findAll('Users', ['firstName' => 'Foo']);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('Foo', $rows[0]['firstName']);
    }

    public function testFindAllWithoutWhere()
    {
        $rows = $this->dataMapper->findAll('Users', null);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('Meta', $rows[0]['firstName']);
    }
}
