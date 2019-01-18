<?php

error_reporting(E_ALL);

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
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'Foo', 'lastName' => 'Bar']);
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'Jane', 'lastName' => 'Doe']);
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'John', 'lastName' => 'Doe']);

        $this->assertInternalType('integer', $insertId);
    }

    public function testFindOne()
    {
        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Foo']);

        $this->assertInternalType('array', $row);

        $this->assertEquals('Foo', $row['firstName']);
    }

    public function testFindAllWithWhere()
    {
        $rows = $this->dataMapper->findAll($this->usersTable, ['firstName' => 'John']);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('John', $rows[0]['firstName']);
    }

    public function testFindAllWithoutWhere()
    {
        $rows = $this->dataMapper->findAll($this->usersTable);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('Foo', $rows[0]['firstName']);
        $this->assertEquals('Jane', $rows[1]['firstName']);
        $this->assertEquals('Doe', $rows[2]['lastName']);
    }

    public function testUpdateWithWhere()
    {
        $where = ['firstName' => 'John'];
        $data = ['firstName' => 'Jane'];
        $this->dataMapper->update($this->usersTable, $data, $where);

        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals('Jane', $row['firstName']);
    }

    public function testUpdateWithoutWhere()
    {
        $data = ['firstName' => 'Jane'];
        $this->dataMapper->update($this->usersTable, $data);

        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals('Jane', $row['firstName']);
    }

    public function testDeleteWithWhere()
    {
        $where = ['firstName' => 'Jane'];
        $this->dataMapper->delete($this->usersTable, $where);

        $row = $this->dataMapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals(null, $row);
    }

    public function testDeleteWithoutWhere()
    {
        $this->testCreate();

        $this->dataMapper->delete($this->usersTable);

        $row = $this->dataMapper->findAll($this->usersTable);

        $this->assertEquals([], $row);
    }
}
