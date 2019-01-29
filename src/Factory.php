<?php

namespace MetaRush\DataMapper;

class Factory
{
    private $adapter = 'AtlasQuery';
    private $dsn;
    private $dbUser;
    private $dbPass;

    public function build(): DataMapper
    {
        // __NAMESPACE__ or fully qualified namespace is required for dynamic use
        $adapter = __NAMESPACE__ . '\Adapters\\' . $this->getAdapter();

        $adapter = new $adapter($this->getDsn(), $this->getDbUser(), $this->getDbPass());

        return new DataMapper($adapter);
    }

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
}
