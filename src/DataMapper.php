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

    public function findAll()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
