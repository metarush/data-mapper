<?php

namespace MetaRush\DataMapper\Adapters;

use Atlas\Query\Delete;
use Atlas\Query\Insert;
use Atlas\Query\Select;
use Atlas\Query\Update;

class AtlasQuery implements AdapterInterface
{
    private $pdo;

    public function __construct(string $dsn,  ? string $dbUsername,  ? string $dbPassword)
    {
        $this->pdo = new \PDO($dsn, $dbUsername, $dbPassword);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function create(string $table, array $data) : int
    {
        $insert = Insert::new ($this->pdo);
        $insert->into($table)->columns($data)->perform();

        return $insert->getLastInsertId();
    }

    public function findOne(string $table, array $where) :  ? array
    {
        $select = Select::new ($this->pdo);

        return $select->columns('*')->from($table)->whereEquals($where)->fetchOne();
    }

    public function findAll(string $table,  ? array $where) : array
    {
        $select = Select::new ($this->pdo);

        $where = $where ?? [];

        return $select->columns('*')->from($table)->whereEquals($where)->fetchAll();
    }

    public function update(string $table, ? array $where, array $set) : void
    {
        $update = Update::new ($this->pdo);

        $where = $where ?? [];

        $update->table($table)->columns($set)->whereEquals($where)->perform();
    }

    public function delete()
    {

    }
}
