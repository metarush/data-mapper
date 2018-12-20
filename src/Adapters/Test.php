<?php

namespace MetaRush\DataMapper\Adapters;

class Test implements AdapterInterface
{
    public function __construct()
    {
    }

    public function create(string $table, array $data) : int
    {
        return 1;
    }

    public function findOne()
    {

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
