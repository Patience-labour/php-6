<?php

namespace App;

class UserTableWrapper implements TableWrapperInterface
{
  private array $rows = [];

  public function insert(array $values): void
  {
    $this->rows[] = $values;
  }

  public function update(int $id, array $values): array
  {
    foreach ($this->rows as &$row) {
      if (isset($row['id']) && $row['id'] === $id) {
        $row = array_merge($row, $values);
        return $row;
      }
    }

    return [];
  }

  public function delete(int $id): void
  {
    $this->rows = array_filter($this->rows, function ($row) use ($id) {
      return !isset($row['id']) || $row['id'] !== $id;
    });
  }

  public function get(): array
  {
    return $this->rows;
  }
}
