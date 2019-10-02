<?php

namespace MetaRush\DataMapper;

class Config
{
    private $adapter = 'AtlasQuery';
    private $dsn;
    private $dbUser;
    private $dbPass;
    private $stripMissingColumns = false;
    private $tablesDefinition;

    public function getAdapter(): string
    {
        return $this->adapter;
    }

    public function setAdapter(string $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getDsn(): string
    {
        return $this->dsn;
    }

    public function setDsn(string $dsn)
    {
        $this->dsn = $dsn;

        return $this;
    }

    public function getDbUser(): ?string
    {
        return $this->dbUser;
    }

    public function setDbUser(?string $dbUser)
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    public function getDbPass(): ?string
    {
        return $this->dbPass;
    }

    public function setDbPass(?string $dbPass)
    {
        $this->dbPass = $dbPass;

        return $this;
    }

    public function getStripMissingColumns(): bool
    {
        return $this->stripMissingColumns;
    }

    public function setStripMissingColumns(bool $stripMissingColumns)
    {
        $this->stripMissingColumns = $stripMissingColumns;

        return $this;
    }

    public function getTablesDefinition(): array
    {
        return $this->tablesDefinition;
    }

    public function setTablesDefinition(array $tablesDefinition)
    {
        $this->tablesDefinition = $tablesDefinition;

        return $this;
    }
}
