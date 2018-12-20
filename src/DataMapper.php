<?php

namespace MetaRush\DataMapper;

class DataMapper
{
    private $adapter;

    public function __construct ($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Creates a new record in the table
     *
     * @param  string $table Name of table to insert to
     * @param  array  $data  Column-value pair of the data to insert
     * @return int The last insert id
     */
    public function create(string $table, array $data): int
    {
        return $this->adapter->create($table, $data);
    }

}
