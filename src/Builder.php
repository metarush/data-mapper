<?php

namespace MetaRush\DataMapper;

class Builder extends Config
{

    public function build(): DataMapper
    {
        // __NAMESPACE__ or fully qualified namespace is required for dynamic use
        $adapter = __NAMESPACE__ . '\Adapters\\' . $this->getAdapter();

        $adapter = new $adapter($this->getDsn(), $this->getDbUser(), $this->getDbPass());

        return new DataMapper($adapter);
    }
}
