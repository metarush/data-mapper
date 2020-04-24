<?php

namespace MetaRush\DataMapper;

class Config
{
    /**
     *
     * @var string
     */
    private $adapter = 'AtlasQuery';

    /**
     *
     * @var string
     */
    private $dsn;

    /**
     *
     * @var ?string
     */
    private $dbUser;

    /**
     *
     * @var ?string
     */
    private $dbPass;

    /**
     *
     * @var bool
     */
    private $stripMissingColumns = false;

    /**
     *
     * @var mixed[]
     */
    private $tablesDefinition;

    public function getAdapter(): string
    {
        return $this->adapter;
    }

    public function setAdapter(string $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getDsn(): string
    {
        return $this->dsn;
    }

    public function setDsn(string $dsn): self
    {
        $this->dsn = $dsn;

        return $this;
    }

    public function getDbUser(): ?string
    {
        return $this->dbUser;
    }

    public function setDbUser(?string $dbUser): self
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    public function getDbPass(): ?string
    {
        return $this->dbPass;
    }

    public function setDbPass(?string $dbPass): self
    {
        $this->dbPass = $dbPass;

        return $this;
    }

    public function getStripMissingColumns(): bool
    {
        return $this->stripMissingColumns;
    }

    public function setStripMissingColumns(bool $stripMissingColumns): self
    {
        $this->stripMissingColumns = $stripMissingColumns;

        return $this;
    }

    /**
     *
     * @return mixed[]
     */
    public function getTablesDefinition(): array
    {
        return $this->tablesDefinition;
    }

    /**
     *
     * @param mixed[] $tablesDefinition
     * @return self
     */
    public function setTablesDefinition(array $tablesDefinition): self
    {
        $this->tablesDefinition = $tablesDefinition;

        return $this;
    }

}