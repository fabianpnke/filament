<?php

namespace Filament\Tables\Concerns;

use Closure;
use Filament\Tables\Columns\Column;

trait HasColumns
{
    protected array $cachedTableColumns;

    public function cacheTableColumns(): void
    {
        $this->cachedTableColumns = [];

        foreach ($this->getTableColumns() as $column) {
            $column->table($this->getCachedTable());

            $this->cachedTableColumns[$column->getName()] = $column;
        }
    }

    public function callTableColumnAction(string $name, string $recordKey)
    {
        $record = $this->getTableRecord($recordKey);

        if (! $record) {
            return;
        }

        $column = $this->getCachedTableColumn($name);

        if (! $column) {
            return;
        }

        if ($column->isHidden()) {
            return;
        }

        $action = $column->getAction();

        if (! ($action instanceof Closure)) {
            return;
        }

        return $column->record($record)->evaluate($action);
    }

    public function getCachedTableColumns(): array
    {
        return $this->cachedTableColumns;
    }

    public function getCachedTableColumn(string $name): ?Column
    {
        return $this->getCachedTableColumns()[$name] ?? null;
    }

    protected function getTableColumns(): array
    {
        return [];
    }
}
