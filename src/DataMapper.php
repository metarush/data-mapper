<?php

namespace MetaRush\DataMapper;

class DataMapper implements Adapters\AdapterInterface
{
    private $adapter;

    public function __construct(Adapters\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function create(string $table, array $data): int
    {
        return $this->adapter->create($table, $data);
    }

    public function findOne(string $table, array $where):  ? array
    {
        return $this->adapter->findOne($table, $where);
    }

    public function findAll(string $table,  ? array $where) : array
    {
        return $this->adapter->findAll($table, $where);
    }

    public function update(string $table, array $data, ? array $where = null) : void
    {
        $this->adapter->update($table, $data, $where);
    }

    public function delete()
    {

    }
}
