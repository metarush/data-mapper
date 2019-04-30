<?php

use MetaRush\DataMapper;
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

        $adapter = new DataMapper\Adapters\AtlasQuery($dsn, null, null);
        $this->mapper = new DataMapper\DataMapper($adapter);

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
            ['firstName' => 'John', 'lastName' => 'Doe', 'age' => 40],
            ['firstName' => 'Bar', 'lastName' => 'Refaeli', 'age' => 25]
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
        $this->assertCount(3, $rows);

        $rows = $this->mapper->findAll($this->usersTable, ['age >= 30']);
        $this->assertCount(2, $rows);

        $rows = $this->mapper->findAll($this->usersTable, ['age BETWEEN 21 AND 39']);
        $this->assertCount(2, $rows);

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

    public function testFindAllOrderBy()
    {
        $rows = $this->mapper->findAll($this->usersTable, [], 'age DESC');
        $this->assertEquals(40, $rows[0]['age']);

        $rows = $this->mapper->findAll($this->usersTable, [], 'firstName');
        $this->assertEquals('Bar', $rows[0]['firstName']);
    }

    public function testFindAllLimitAndOffset()
    {
        $rows = $this->mapper->findAll($this->usersTable, [], null, 2);
        $this->assertCount(2, $rows);

        $rows = $this->mapper->findAll($this->usersTable, [], null, 2, 2);

        $this->assertCount(2, $rows);
        $this->assertEquals('John', $rows[0]['firstName']);
    }

    public function testTransactions()
    {
        // test rollback
        $this->mapper->beginTransaction();
        $data = ['firstName' => 'Alice'];
        $this->mapper->create($this->usersTable, $data);
        $data = ['firstName' => 'Bob'];
        $this->mapper->create($this->usersTable, $data);
        $this->mapper->rollBack();

        $where = ['firstName' => 'Alice'];
        $rows = $this->mapper->findOne($this->usersTable, $where);
        $this->assertNull($rows);

        $where = ['firstName' => 'Bob'];
        $rows = $this->mapper->findOne($this->usersTable, $where);
        $this->assertNull($rows);

        // test commit
        $this->mapper->beginTransaction();
        $data = ['firstName' => 'Alice'];
        $this->mapper->create($this->usersTable, $data);
        $data = ['firstName' => 'Bob'];
        $this->mapper->create($this->usersTable, $data);
        $this->mapper->commit();

        $where = ['firstName' => 'Alice'];
        $rows = $this->mapper->findOne($this->usersTable, $where);
        $this->assertEquals('Alice', $rows['firstName']);

        $where = ['firstName' => 'Bob'];
        $rows = $this->mapper->findOne($this->usersTable, $where);
        $this->assertEquals('Bob', $rows['firstName']);
    }
}
