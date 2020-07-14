# metarush/data-mapper

A generic data access layer for basic CRUD operations.
Can act as a layer between database and repositories/services.

---

## Install

Install via composer as `metarush/data-mapper`

## Usage

### Init library

```php
<?php

$builder = (new \MetaRush\DataMapper\Builder)
    ->setDsn('mysql:host=localhost;dbname=example') // PDO DSN
    ->setDbUser('foo')
    ->setDbPass('bar');

$dM = $builder->build();
```

### Create new row

```php
// insert 'foo' in column 'col1' and 'bar' in column 'col2'
$data = [
    'col1' => 'foo',
    'col2' => 'bar'
];
$dM->create('table', $data);
```

### Find column

```php
// find value of column 'col2' where 'col1' == 'foo'
$column = $dM->findColumn('table', ['col1' => 'foo'], 'col2');
print_r($column); // bar
```

### Find row

```php
// find row where column 'col1' == 'foo'
$row = $dM->findOne('table', ['col1' => 'foo']);
print_r($row);
```

### Find rows

```php
// find all rows
$rows = $dM->findAll('table');
print_r($rows);

// find rows where column 'col1' = 'foo'
$rows = $dM->findAll('table', ['col1' => 'foo']);
print_r($rows);

// find rows where column 'col1' = 'foo', order by col1 DESC
$rows = $dM->findAll('table', ['col1' => 'foo'], 'col1 DESC');
print_r($rows);

// find rows where column 'col1' = 'foo', order by col2 DESC, limit 2, offset 3
$rows = $dM->findAll('table', ['col1' => 'foo'], 'col2 DESC', 2, 3);
print_r($rows);

// find rows grouped by column 'col1'
$dM->groupBy('col1');
$rows = $dM->findAll('table');
print_r($rows);
```

### Update rows

```php
$data = ['col1' => 'bar'];
$where = ['col2' => 'foo'];
$dM->update('table', $data, $where);
```

### Delete rows

```php
$where = ['col1' => 'foo'];
$dM->delete('table', $where);
```

### Using `$where` clause

As per `Atlas.Query` documentation, if the value of the column given is an array, the condition will be IN (). Given a null value, the condition will be IS NULL. For all other values, the condition will be =. If you pass a key without a value, that key will be used as a raw unescaped condition.

```php
$where = [
    'foo' => ['a', 'b', 'c'],
    'bar' => null,
    'baz' => 'dib',
    'zim = NOW()'
];
```

The above sample is equivalent to
`WHERE foo IN (:__1__, :__2__, :__3__) AND bar IS NULL AND baz = :__4__ AND zim = NOW()`

Other examples using other `WHERE` operators:

```php
$where = [
    'foo > 20',
    'bar <= 30',
    'baz BETWEEN 5 AND 10',
    "firstName LIKE 'test%'"
];
```

Remember, if you pass a key without a value (like these other `WHERE` operators), they will be unescaped.

### Transaction methods

```php
$dM->beginTransaction();
$dM->commit();
$dM->rollBack();
```

### Optional config/builder methods

```php
->setStripMissingColumns(true);
```

If set to`true`,`create()`and `update()` methods will strip missing columns in their `$data`  parameter.

```php
->setTablesDefinition(array $tablesDefinition);
```

Required when using `setStripMissingColumns(true)`

Example parameter for `$tablesDefinition`:

```php
$tablesDefinition = [
    'UsersTable' => [ // table name
        'id', 'firstName', 'lastName' // column names
    ],
    'PostsTable' => [ // table name
        'id', 'subject', 'message' // columns names
    ]
];
```

---

## Current adapters

- PDO (via Atlas.Query)
