<?php

namespace MetaRush\DataMapper;

class DataMapper implements Adapters\AdapterInterface
{
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
    public function findOne(string $table, array $where): ?array
    {
        return $this->adapter->findOne($table, $where);
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $table, ?array $where = null): array
    {
        return $this->adapter->findAll($table, $where);
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
}
