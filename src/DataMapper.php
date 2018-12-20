<?php

namespace MetaRush\DataMapper;

class DataMapper
{
    private $adapter;

    public function __construct (AdapterInterface $adapter)
    {
        return $adapter;
    }
}
