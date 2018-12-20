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

	public function findOne();

	public function findAll();

	public function update();

	public function delete();
}