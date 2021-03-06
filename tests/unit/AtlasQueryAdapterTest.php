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
        if (!\file_exists($this->dbFile)) {

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

        $tablesDefinition = [
            'Users' => [
                'id', 'firstName', 'lastName', 'age'
            ]
        ];

        $cfg = (new DataMapper\Config)
            ->setDsn($dsn)
            ->setTablesDefinition($tablesDefinition)
            ->setStripMissingColumns(true);

        $adapter = new DataMapper\Adapters\AtlasQuery($cfg);
        $this->mapper = new DataMapper\DataMapper($adapter);

        $this->seedTestData();
    }

    public function tearDown()
    {
        // close the DB connections so unlink will work
        unset($this->mapper);
        unset($this->pdo);

        if (file_exists($this->dbFile))
            \unlink($this->dbFile);
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

        $this->assertEquals(5, $insertId);

        $this->assertEquals(5, $this->mapper->getLastInsertId());
    }

    public function testFindOne()
    {
        $row = $this->mapper->findOne($this->usersTable, ['firstName' => 'Foo']);

        $this->assertInternalType('array', $row);

        $this->assertEquals('Foo', $row['firstName']);
    }

    public function testFindColumn()
    {
        $column = $this->mapper->findColumn($this->usersTable, ['firstName' => 'Bar'], 'lastName');
        $this->assertEquals('Refaeli', $column);

        $column = $this->mapper->findColumn($this->usersTable, ['firstName' => 'Jane'], 'age');

        $this->assertEquals(30, $column);
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

    public function testStripMissingColumnsCreateAndUpdate()
    {
        $data = [
            'id'                => 9,
            'firstName'         => 'Foo',
            'lastName'          => 'Bar',
            'age'               => 10,
            'nonExistentColumn' => 'qux'
        ];

        $this->mapper->create($this->usersTable, $data);

        $where = ['id' => 9];
        $row = $this->mapper->findOne($this->usersTable, $where);
        $this->assertEquals(10, $row['age']);

        // ------------------------------------------------

        $data = [
            'age'                      => 11,
            'anotherNonExistentColumn' => 'qux'
        ];

        $where = ['id' => 9];

        $this->mapper->update($this->usersTable, $data, $where);

        $row = $this->mapper->findOne($this->usersTable, $where);
        $this->assertEquals(11, $row['age']);
    }

    public function testStripMissingColumnsMissingTableInDefinition()
    {
        $this->expectExceptionMessageRegExp('/not defined in your tables definition/');

        $this->mapper->create('nonExistentTable', []);
    }

    public function testFindAllWithGroupBy()
    {
        $this->mapper->groupBy('lastName');
        $rows = $this->mapper->findAll($this->usersTable);

        $this->assertCount(3, $rows);
    }

    public function testQueryWithCustomSql()
    {
        $preparedStatement = 'SELECT * FROM ' . $this->usersTable . ' WHERE age < ?';
        $bindParams = [30];
        $rows = $this->mapper->query($preparedStatement, $bindParams);

        $this->assertCount(2, $rows);

        $this->assertEquals('Foo', $rows[0]['firstName']);
        $this->assertEquals('Bar', $rows[1]['firstName']);
    }

    public function testExecWithSingleInsert()
    {
        $preparedStatement = "INSERT INTO $this->usersTable (firstName, lastName, age) VALUES (?, ?, ?)";
        $bindParams = ['Mark', 'Calaway', '31'];
        $numRows = $this->mapper->exec($preparedStatement, $bindParams);

        $this->assertEquals(1, $numRows);
    }

    public function testExecWithMultipleInsert()
    {
        $preparedStatement = "INSERT INTO $this->usersTable (firstName, lastName, age) VALUES (?, ?, ?), (?, ?, ?)";
        $bindParams = ['Mark', 'Calaway', null, 'Dwayne', 'Johnson', '32'];
        $numRows = $this->mapper->exec($preparedStatement, $bindParams);

        $this->assertEquals(2, $numRows);

        // ------------------------------------------------

        /* also test getLastInsertId() */
        $this->assertEquals(6, $this->mapper->getLastInsertId());
    }

    public function testExecWithUpdate()
    {
        $preparedStatement = "UPDATE $this->usersTable SET age = ? WHERE lastName = 'Doe'";
        $bindParams = ['18'];
        $numRows = $this->mapper->exec($preparedStatement, $bindParams);

        $this->assertEquals(2, $numRows);
    }

    public function testExecWithDelete()
    {
        $preparedStatement = "DELETE FROM $this->usersTable WHERE lastName = ?";
        $bindParams = ['Doe'];
        $numRows = $this->mapper->exec($preparedStatement, $bindParams);

        $this->assertEquals(2, $numRows);
    }

}