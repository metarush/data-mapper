<?php

namespace MetaRush\DataMapper\Adapters;

use Atlas\Query\Insert;
use Atlas\Query\Select;
use Atlas\Query\Update;
use Atlas\Query\Delete;

class AtlasQuery implements AdapterInterface
{
    private $pdo;

    public function __construct(string $dsn, ?string $dbUsername, ?string $dbPassword)
    {
        $this->pdo = new \PDO($dsn, $dbUsername, $dbPassword);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @inheritDoc
     */
    public function create(string $table, array $data): int
    {
        $insert = Insert::new($this->pdo);
        $insert->into($table)->columns($data)->perform();

        return $insert->getLastInsertId();
    }

    /**
     * @inheritDoc
     */
    public function findOne(string $table, array $where): ?array
    {
        $select = Select::new($this->pdo);

        return $select->columns('*')->from($table)->whereEquals($where)->fetchOne();
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $table, ?array $where = null, ?string $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $select = Select::new($this->pdo);

        $where = $where ?? [];

        $query = $select->columns('*')->from($table)->whereEquals($where);

        if ($orderBy)
            $query->orderBy($orderBy);

        if ($limit)
            $query->limit($limit);

        if ($offset)
            $query->offset($offset);

        return $query->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function update(string $table, array $data, ?array $where = null): void
    {
        $update = Update::new($this->pdo);

        $where = $where ?? [];

        $update->table($table)->columns($data)->whereEquals($where)->perform();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $table, ?array $where = null): void
    {
        $delete = Delete::new($this->pdo);

        $where = $where ?? [];

        $delete->from($table)->whereEquals($where)->perform();
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }
}
