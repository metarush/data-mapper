<?php

use MetaRush\DataMapper\Adapters\AtlasQuery;
use MetaRush\DataMapper\DataMapper;
use PHPUnit\Framework\TestCase;

class AtlasQueryAdapterTest extends TestCase
{
    private $dataMapper;
    private $usersTable;

    public function setUp()
    {
        $dsn = 'sqlite:' . __DIR__ . '/test.db';
        $adapter = new AtlasQuery($dsn, null, null);
        $this->dataMapper = new DataMapper($adapter);
        $this->usersTable = 'Users';
    }

    public function testCreate()
    {
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'Meta', 'lastName' => 'Rush']);
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'Foo', 'lastName' => 'Bar']);
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'John', 'lastName' => 'Doe']);

        $this->assertInternalType('integer', $insertId);
    }

    public function testFindOne()
    {
        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Meta']);

        $this->assertInternalType('array', $row);

        $this->assertEquals('Meta', $row['firstName']);
    }

    public function testFindAllWithWhere()
    {
        $rows = $this->dataMapper->findAll($this->usersTable, ['firstName' => 'Foo']);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('Foo', $rows[0]['firstName']);
    }

    public function testFindAllWithoutWhere()
    {
        $rows = $this->dataMapper->findAll($this->usersTable, null);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('Meta', $rows[0]['firstName']);
        $this->assertEquals('Foo', $rows[1]['firstName']);
        $this->assertEquals('Doe', $rows[2]['lastName']);
    }

    public function testUpdateWithWhere()
    {
        $where = ['firstName' => 'John'];
        $set = ['firstName' => 'Jane'];
        $this->dataMapper->update($this->usersTable, $where, $set);

        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals('Jane', $row['firstName']);
    }

    public function testUpdateWithoutWhere()
    {
        $set = ['firstName' => 'Jane'];
        $this->dataMapper->update($this->usersTable, null, $set);

        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals('Jane', $row['firstName']);
    }
}
