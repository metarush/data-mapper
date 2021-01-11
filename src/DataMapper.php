<?php

namespace MetaRush\DataMapper;

class DataMapper implements Adapters\AdapterInterface
{
    /**
     *
     * @var Adapters\AdapterInterface
     */
    private $adapter;

    public function __construct(Adapters\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritDoc
     */
    public function create(string $table, array $data): int
    {
        return $this->adapter->create($table, $data);
    }

    /**
     * @inheritDoc
     */
    public function findColumn(string $table, array $where, string $column): ?string
    {
        return $this->adapter->findColumn($table, $where, $column);
    }

    /**
     * @inheritDoc
     */
    public function findOne(string $table, array $where): ?array
    {
        return $this->adapter->findOne($table, $where);
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $table, ?array $where = null, ?string $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->adapter->findAll($table, $where, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function update(string $table, array $data, ?array $where = null): void
    {
        $this->adapter->update($table, $data, $where);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $table, ?array $where = null): void
    {
        $this->adapter->delete($table, $where);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): void
    {
        $this->adapter->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): void
    {
        $this->adapter->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): void
    {
        $this->adapter->rollBack();
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): void
    {
        $this->adapter->groupBy($column);
    }

    /**
     * @inheritDoc
     */
    public function query(string $preparedStatement, ?array $bindParams = null, ?int $fetchStyle = null): array
    {
        return $this->adapter->query($preparedStatement, $bindParams, $fetchStyle);
    }

}