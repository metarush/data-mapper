<?php

namespace MetaRush\DataMapper\Adapters;

interface AdapterInterface
{

    /**
     * Creates new record in a table
     *
     * @param string $table Name of table to insert to
     * @param array $data Column-value pair of the data to insert
     * @return int The last insert id
     */
    public function create(string $table, array $data): int;

    /**
     * Find record in a table
     *
     * @param string $table Name of table to query
     * @param array $where Column-value pair of the where clause
     * @return array|null The record
     */
    public function findOne(string $table, array $where): ?array;

    /**
     * Find all records in a table
     *
     * @param string $table Name of table to query
     * @param array $where Column-value pair of the where clause
     * @return array The records
     */
    public function findAll(string $table, ?array $where): array;

    /**
     * Update record in a table
     *
     * @param string $table Name of table to update
     * @param array $data Data to be updated
     * @param array $where Column-value pair of the where clause
     * @return void
     */
    public function update(string $table, array $data, ?array $where): void;

    /**
     * Delete record in a table
     *
     * @param string $table Name of table where a record will be deleted
     * @param array $where Column-value pair of the where clause
     * @return void
     */
    public function delete(string $table, ?array $where): void;
}
