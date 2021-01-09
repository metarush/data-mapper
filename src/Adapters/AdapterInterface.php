<?php

namespace MetaRush\DataMapper\Adapters;

interface AdapterInterface
{
    /**
     * Creates new record in a table
     *
     * @param string $table Name of table to insert to
     * @param mixed[] $data Column-value pair of the data to insert
     * @return int The last insert id
     */
    public function create(string $table, array $data): int;

    /**
     * Find column value in a table
     *
     * @param string $table
     * @param mixed[] $where
     * @param string $column
     * @return string|null
     */
    public function findColumn(string $table, array $where, string $column): ?string;

    /**
     * Find record in a table
     *
     * @param string $table Name of table to query
     * @param mixed[] $where Column-value pair of the where clause
     * @return mixed[]|null The record
     */
    public function findOne(string $table, array $where): ?array;

    /**
     * Find all records in a table
     *
     * @param string $table Name of table to query
     * @param mixed[] $where Column-value pair of the where clause
     * @param string|null $orderBy Order by column
     * @param int|null $limit Limit the number of records to return
     * @param int|null $offset Skip records up to $offset
     * @return mixed[]
     */
    public function findAll(string $table, ?array $where, ?string $orderBy, ?int $limit, ?int $offset): array;

    /**
     * Update record in a table
     *
     * @param string $table Name of table to update
     * @param mixed[] $data Data to be updated
     * @param mixed[] $where Column-value pair of the where clause
     * @return void
     */
    public function update(string $table, array $data, ?array $where): void;

    /**
     * Delete record in a table
     *
     * @param string $table Name of table where a record will be deleted
     * @param mixed[] $where Column-value pair of the where clause
     * @return void
     */
    public function delete(string $table, ?array $where): void;

    /**
     * Begin transaction
     *
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * Commit transaction
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Rollback transaction
     *
     * @return void
     */
    public function rollBack(): void;

    /**
     * Set GROUP BY clause that will be used by findAll()
     *
     * @param string $column
     * @return void
     */
    public function groupBy(string $column): void;

    /**
     * Custom SQL query
     *
     * @param string $preparedStatement
     * @param mixed[] $bindParams
     * @return mixed[]
     */
    public function query (string $preparedStatement, ?array $bindParams = null): array;
}