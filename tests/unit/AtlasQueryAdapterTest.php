<?php

error_reporting(E_ALL);

use MetaRush\DataMapper\DataMapper;
use PHPUnit\Framework\TestCase;

class AtlasQueryAdapterTest extends TestCase
{
    private $mapper;
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
                `lastName`	TEXT,
                `age`       INTEGER
            )');
        }

        //$adapter = new AtlasQuery($dsn, null, null);
        //$this->mapper = new DataMapper($adapter);

        $factory = (new \MetaRush\DataMapper\Factory())
            ->setDsn($dsn);

        $this->mapper = $factory->build();

        $this->seedTestData();
    }

    public function tearDown()
    {
        // close the DB connections so unlink will work
        unset($this->mapper);
        unset($this->pdo);

        if (file_exists($this->dbFile))
            unlink($this->dbFile);
    }

    public function seedTestData()
    {
        $data = [
            ['firstName' => 'Foo', 'lastName' => 'Bar', 'age' => 20],
            ['firstName' => 'Jane', 'lastName' => 'Doe', 'age' => 30],
            ['firstName' => 'John', 'lastName' => 'Doe', 'age' => 40]
        ];

        foreach ($data as $v)
            $this->mapper->create($this->usersTable, $v);
    }

    public function testCreate()
    {
        $insertId = $this->mapper->create($this->usersTable, ['firstName' => 'Quz', 'lastName' => 'Test']);

        $this->assertInternalType('integer', $insertId);
    }

    public function testFindOne()
    {
        $row = $this->mapper->findOne($this->usersTable, ['firstName' => 'Foo']);

        $this->assertInternalType('array', $row);

        $this->assertEquals('Foo', $row['firstName']);
    }

    public function testFindAllWithWhere()
    {
        $rows = $this->mapper->findAll($this->usersTable, ['firstName' => 'John']);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('John', $rows[0]['firstName']);
    }

    public function testFindAllUsingOtherWhereOperators()
    {
        $rows = $this->mapper->findAll($this->usersTable, ['age > 20']);
        $this->assertCount(2, $rows);

        $rows = $this->mapper->findAll($this->usersTable, ['age >= 30']);
        $this->assertCount(2, $rows);

        $rows = $this->mapper->findAll($this->usersTable, ['age BETWEEN 21 AND 39']);
        $this->assertCount(1, $rows);

        $rows = $this->mapper->findAll($this->usersTable, ["firstName LIKE 'J%'"]);
        $this->assertCount(2, $rows);
    }

    public function testFindAllWithoutWhere()
    {
        $rows = $this->mapper->findAll($this->usersTable);

        $this->assertInternalType('array', $rows);

        $this->assertEquals('Foo', $rows[0]['firstName']);
        $this->assertEquals('Jane', $rows[1]['firstName']);
        $this->assertEquals('Doe', $rows[2]['lastName']);
    }

    public function testUpdateWithWhere()
    {
        $where = ['firstName' => 'John'];
        $data = ['firstName' => 'Jane'];
        $this->mapper->update($this->usersTable, $data, $where);

        $row = $this->mapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals('Jane', $row['firstName']);
    }

    public function testUpdateWithoutWhere()
    {
        $data = ['firstName' => 'Jane'];
        $this->mapper->update($this->usersTable, $data);

        $row = $this->mapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals('Jane', $row['firstName']);
    }

    public function testDeleteWithWhere()
    {
        $where = ['firstName' => 'Jane'];
        $this->mapper->delete($this->usersTable, $where);

        $row = $this->mapper->findOne($this->usersTable, ['firstName' => 'Jane']);

        $this->assertEquals(null, $row);
    }

    public function testDeleteWithoutWhere()
    {
        $this->mapper->delete($this->usersTable);

        $row = $this->mapper->findAll($this->usersTable);

        $this->assertEquals([], $row);
    }
}
