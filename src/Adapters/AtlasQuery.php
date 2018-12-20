<?php

namespace MetaRush\DataMapper\Adapters;

use Atlas\Query\Select;
use Atlas\Query\Insert;
use Atlas\Query\Update;
use Atlas\Query\Delete;

class AtlasQuery implements AdapterInterface
{
	private $pdo;

	public function __construct($dsn, $dbUsername, $dbPassword)
	{
        $this->pdo = new \PDO($dsn, $dbUsername, $dbPassword);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

    public function create(string $table, array $data)
    {
         $insert = Insert::new($this->pdo);
         $insert->into($table)->columns($data)->perform();

         return $insert->getLastInsertId();
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
