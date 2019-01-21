# metarush/data-mapper

A generic data mapper library that can act as a layer between database and repositories/services.

---

## Usage

### Init library

    <?php

    use MetaRush\DataMapper\Adapters\AtlasQuery;
    use MetaRush\DataMapper\DataMapper;

    // db info
    $dsn = 'mysql:host=localhost;dbname=example';
	$dbUser = 'example';
	$dbPass = 'example';
    $table = 'example';

    $adapter = new AtlasQuery($dsn, $dbUser, $dbPass);
    $dM = new DataMapper($adapter);

### Create new record

    // insert 'foo' in column 'col1' and 'bar' in column 'col2'

    $data = [
        'col1' => 'foo',
        'col2' => 'bar'
    ];

    $dM->create($table, $data);

### Find one record

    // find 'foo' in column 'col'
    $dM->findOne($table, ['col' => 'foo']);

### Find all records

    // find all records
    $dM->findAll($table);

    // find records where column 'col' = 'foo'
    $dM->findAll($table, ['col' => 'foo']);

### Update record

    $data = ['col1' => 'bar'];
    $where = ['col2' => 'foo'];
    $dM->update($table, $data, $where);

### Delete record

    $where = ['col1' => 'foo'];
    $dM->delete($table, $where);

---

## Current adapters

- PDO (via Atlas.Query)

