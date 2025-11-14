<?php

namespace App;

interface TableWrapperInterface
{
  public function insert(array $values): void;
  public function update(int $id, array $values): array;
  public function delete(int $id): void;
  public function get(): array;
}
