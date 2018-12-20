<?php

namespace MetaRush\DataMapper\Adapters;

interface AdapterInterface;
{
	public function create();

	public function findOne();

	public function findAll();

	public function update();

	public function delete();
}