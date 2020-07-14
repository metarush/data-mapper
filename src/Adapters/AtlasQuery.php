<?php

namespace MetaRush\DataMapper\Adapters;

use MetaRush\DataMapper\Config;
use Atlas\Query\Insert;
use Atlas\Query\Select;
use Atlas\Query\Update;
use Atlas\Query\Delete;

class AtlasQuery implements AdapterInterface
{
    /**
     *
     * @var Config
     */
    private $cfg;

    /**
     *
     * @var \PDO
     */
    private $pdo;

    /**
     *
     * @var string|null
     */
    private $groupByColumn;

    public function __construct(Config $cfg)
    {
        $this->cfg = $cfg;

        $this->pdo = new \PDO($cfg->getDsn(), $cfg->getDbUser(), $cfg->getDbPass());
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @inheritDoc
     */
    public function create(string $table, array $data): int
    {
        if ($this->cfg->getStripMissingColumns())
            $data = $this->getStrippedMissingColumns($table, $data);

        $insert = Insert::new($this->pdo);
        $insert->into($table)->columns($data)->perform();

        return $insert->getLastInsertId();
    }

    public function findColumn(string $table, array $where, string $column): ?string
    {
        $select = Select::new($this->pdo);

        $row = $select->columns('*')->from($table)->whereEquals($where)->fetchOne();

        $column = $row ? $row[$column] : null;

        return $column;
    }

    /**
     * @inheritDoc
     */
    public function findOne(string $table, array $where): ?array
    {
        $select = Select::new($this->pdo);

        return $select->columns('*')->from($table)->whereEquals($where)->fetchOne();
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $table, ?array $where = null, ?string $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $select = Select::new($this->pdo);

        $where = $where ?? [];

        $query = $select->columns('*')->from($table)->whereEquals($where);

        if ($this->groupByColumn) {
            $query->groupBy($this->groupByColumn);
            $this->groupByColumn = null;
        }

        if ($orderBy)
            $query->orderBy($orderBy);

        if ($limit)
            $query->limit($limit);

        if ($offset)
            $query->offset($offset);

        return $query->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function update(string $table, array $data, ?array $where = null): void
    {
        if ($this->cfg->getStripMissingColumns())
            $data = $this->getStrippedMissingColumns($table, $data);

        $update = Update::new($this->pdo);

        $where = $where ?? [];

        $update->table($table)->columns($data)->whereEquals($where)->perform();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $table, ?array $where = null): void
    {
        $delete = Delete::new($this->pdo);

        $where = $where ?? [];

        $delete->from($table)->whereEquals($where)->perform();
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }

    /**
     * Strip missing columns in $data if they don't exist in Config::$tableDefinition
     *
     * @param string $table
     * @param mixed[] $data
     * @return mixed[]
     * @throws \MetaRush\DataMapper\Exception
     */
    protected function getStrippedMissingColumns(string $table, array $data): array
    {
        $tablesDefinition = $this->cfg->getTablesDefinition();

        if (!isset($tablesDefinition[$table]))
            throw new \MetaRush\DataMapper\Exception('Table "' . $table . '" is not defined in your tables definition');

        foreach ($data as $column => $v)
            if (!\in_array($column, $tablesDefinition[$table]))
                unset($data[$column]);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): void
    {
        $this->groupByColumn = $column;
    }

}