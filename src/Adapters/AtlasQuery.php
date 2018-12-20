<?php

namespace MetaRush\OtpAuth\DataMapperAdapters;

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
        return Insert::new($this->pdo)->into($table)->columns($data)->perform()->getLastInsertId();
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
