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

    // current adapter is PDO (via AtlasQuery)
    $adapter = new AtlasQuery($dsn, $dbUser, $dbPass);

    // init data mapper
    $dM = new DataMapper($adapter);

### Create new row

    // insert 'foo' in column 'col1' and 'bar' in column 'col2'
    $data = [
        'col1' => 'foo',
        'col2' => 'bar'
    ];
    $dM->create($table, $data);

### Find row

    // find 'foo' in column 'col'
    $row = $dM->findOne($table, ['col' => 'foo']);
    print_r($row);

### Find rows

    // find all rows
    $rows = $dM->findAll($table);
    print_r($rows);

    // find rows where column 'col' = 'foo'
    $rows = $dM->findAll($table, ['col' => 'foo']);
    print_r($rows);

### Update rows

    $data = ['col1' => 'bar'];
    $where = ['col2' => 'foo'];
    $dM->update($table, $data, $where);

### Delete rows

    $where = ['col1' => 'foo'];
    $dM->delete($table, $where);

### Using `$where` clause

As per `Atlas.Query` documentation, if the value of the column given is an array, the condition will be IN (). Given a null value, the condition will be IS NULL. For all other values, the condition will be =. If you pass a key without a value, that key will be used as a raw unescaped condition.

    $where = [
        'foo' => ['a', 'b', 'c'],
        'bar' => null,
        'baz' => 'dib',
        'zim = NOW()'
    ];

The above sample is equivalent to
`WHERE foo IN (:__1__, :__2__, :__3__) AND bar IS NULL AND baz = :__4__ AND zim = NOW()`

Other examples using other `WHERE` operators:

    $where = [
        'foo > 20',
        'bar <= 30',
        'baz BETWEEN 5 AND 10',
        "firstName LIKE 'test%'"
    ];

Remember, if you pass a key without a value (like these other `WHERE` operators), they will be unescaped.

---

## Current adapters

- PDO (via Atlas.Query)