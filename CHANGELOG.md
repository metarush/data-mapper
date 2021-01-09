# CHANGELOG

## 1.6.1 - 2021-01-09

### Fixed

- Make 2nd param of query() service method optional.

## 1.6.0 - 2021-01-09

### Added

- Add query() service method for custom queries.

## 1.5.0 - 2020-07-14

### Added

- Add groupBy() service method.

## 1.4.0 - 2020-04-25

### Added

- Add findColumn() service method.
- Add PHPStan and follow code suggestions from it.

## 1.3.1 - 2020-01-24

### Added

- Throw exception when a table is not defined in setTablesDefinition(), when using getStrippedMissingColumns() in AtlasQuery adapter.

## 1.3.0 - 2019-10-03

### Added

- Add setStripMissingColumns() config method.
- Add setTablesDefinition() config method.

## 1.2.0 - 2019-05-01

### Added

- Add transaction methods beginTransaction() commit() rollBack().

## 1.1.0 - 2019-04-18

### Added

- Add $orderBy parameter in findAll() service method.
- Add $limit and $offset parameter in findAll() service method.

## 1.0.4 - 2019-01-31

### Changed

- Replace Factory class with Builder class.
- Note this version should've been 2.0.0 due to change in functionality. Nonetheless, this was a quick update and no existing user would've been affected (no one's using this yet).

## 1.0.3 - 2019-01-30

### Added

- Allow null dbUser and dbPass.
- Add separate Config class.

## 1.0.2 - 2019-01-30

### Added

- Add Factory class.

## 1.0.1 - 2019-01-29

### Added

- Add tests for WHERE clause.

## 1.0.0 - 2019-01-22

- Release first version.