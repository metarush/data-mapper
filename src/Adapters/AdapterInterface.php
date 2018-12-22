<?php

namespace MetaRush\DataMapper\Adapters;

interface AdapterInterface
{
    /**
     * Creates a new record in a table
     *
     * @param  string $table Name of table to insert to
     * @param  array  $data  Column-value pair of the data to insert
     * @return int The last insert id
     */
    public function create(string $table, array $data): int;

    public function findOne(string $table, array $where):  ? array;

    public function findAll(string $table, array $where) : array;

    public function update(string $table,  array $data, ? array $where = null) : void;

    public function delete();
}
