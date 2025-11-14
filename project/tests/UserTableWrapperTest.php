<?php

namespace Tests;

use App\UserTableWrapper;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/TableWrapperInterface.php';
require_once __DIR__ . '/../src/UserTableWrapper.php';

class UserTableWrapperTest extends TestCase
{
  private UserTableWrapper $table;

  protected function setUp(): void
  {
    $this->table = new UserTableWrapper();
  }

  public function testInsert(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);
    $result = $this->table->get();

    $this->assertCount(1, $result);
    $this->assertEquals(['id' => 1, 'name' => 'John'], $result[0]);
  }

  public function testGet(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);
    $this->table->insert(['id' => 2, 'name' => 'Jane']);

    $result = $this->table->get();

    $this->assertCount(2, $result);
    $this->assertEquals('John', $result[0]['name']);
    $this->assertEquals('Jane', $result[1]['name']);
  }

  public function testUpdate(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);
    $this->table->insert(['id' => 2, 'name' => 'Jane']);

    $result = $this->table->update(1, ['name' => 'John Updated']);

    $this->assertEquals(['id' => 1, 'name' => 'John Updated'], $result);

    $allData = $this->table->get();
    $this->assertEquals('John Updated', $allData[0]['name']);
  }

  public function testUpdateNonExisting(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);

    $result = $this->table->update(999, ['name' => 'Non-existing']);

    $this->assertEquals([], $result);
  }

  public function testDelete(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);
    $this->table->insert(['id' => 2, 'name' => 'Jane']);

    $this->table->delete(1);
    $result = $this->table->get();

    $this->assertCount(1, $result);

    $this->assertContains(['id' => 2, 'name' => 'Jane'], $result);
  }

  public function testDeleteNonExisting(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);

    $initialCount = count($this->table->get());
    $this->table->delete(999);
    $result = $this->table->get();

    $this->assertCount($initialCount, $result);
  }

  public function testMultipleOperations(): void
  {
    $this->table->insert(['id' => 1, 'name' => 'John']);
    $this->table->insert(['id' => 2, 'name' => 'Jane']);

    $this->assertCount(2, $this->table->get());

    $this->table->update(1, ['name' => 'John Updated']);

    $data = $this->table->get();
    $this->assertEquals('John Updated', $data[0]['name']);

    $this->table->delete(2);

    $this->assertCount(1, $this->table->get());
  }

  public function testInsertMaintainsOrder(): void
  {
    $data1 = ['id' => 1, 'name' => 'First'];
    $data2 = ['id' => 2, 'name' => 'Second'];

    $this->table->insert($data1);
    $this->table->insert($data2);

    $result = $this->table->get();

    $this->assertEquals($data1, $result[0]);
    $this->assertEquals($data2, $result[1]);
  }
}
