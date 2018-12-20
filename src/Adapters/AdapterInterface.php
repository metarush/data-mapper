<?php

namespace MetaRush\DataMapper\Adapters;

interface AdapterInterface
{
	public function create(string $table, array $data);

	public function findOne();

	public function findAll();

	public function update();

	public function delete();
}