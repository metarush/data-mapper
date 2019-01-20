<?php

error_reporting(E_ALL);

use MetaRush\DataMapper\Adapters\AtlasQuery;
use MetaRush\DataMapper\DataMapper;
use PHPUnit\Framework\TestCase;

class AtlasQueryAdapterTest extends TestCase
{
    private $dataMapper;
    private $usersTable;
    private $pdo;
    private $dbFile;

    public function setUp()
    {
        $this->dbFile = __DIR__ . '/test.db';
        $this->usersTable = 'Users';

        $dsn = 'sqlite:' . $this->dbFile;

        // create test db if doesn't exist yet
        if (!file_exists($this->dbFile)) {

            $this->pdo = new \PDO($dsn);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->pdo->query('
                CREATE TABLE `' . $this->usersTable . '` (
                `id`        INTEGER PRIMARY KEY AUTOINCREMENT,
                `firstName`	TEXT,
                `lastName`	TEXT
            )');
        }

        $adapter = new AtlasQuery($dsn, null, null);
        $this->dataMapper = new DataMapper($adapter);

        $this->seedTestData();
    }

    public function tearDown()
    {
        // close the DB connections so unlink will work
        unset($this->dataMapper);
        unset($this->pdo);

        if (file_exists($this->dbFile))
            unlink($this->dbFile);
    }

    public function seedTestData()
    {
        $data = [
            ['firstName' => 'Foo', 'lastName' => 'Bar'],
            ['firstName' => 'Jane', 'lastName' => 'Doe'],
            ['firstName' => 'John', 'lastName' => 'Doe']
        ];

        foreach ($data as $v)
            $this->dataMapper->create($this->usersTable, $v);
    }

    public function testCreate()
    {
        $insertId = $this->dataMapper->create($this->usersTable, ['firstName' => 'Quz', 'lastName' => 'Test']);

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
        $this->dataMapper->delete($this->usersTable);

        $row = $this->dataMapper->findAll($this->usersTable);

        $this->assertEquals([], $row);
    }
}
