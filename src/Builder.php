<?php

namespace MetaRush\DataMapper;

class Builder extends Config
{
    public function build(): DataMapper
    {
        // __NAMESPACE__ or fully qualified namespace is required for dynamic use
        $adapter = __NAMESPACE__ . '\Adapters\\' . $this->getAdapter();

        // comment is for phpstan
        /** @var \MetaRush\DataMapper\Adapters\AdapterInterface $adapter  */
        $adapter = new $adapter($this);

        return new DataMapper($adapter);
    }

}