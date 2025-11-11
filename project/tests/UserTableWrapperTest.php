<?php

namespace Tests;

use App\UserTableWrapper;
use PHPUnit\Framework\TestCase;

class UserTableWrapperExtendedTest extends TestCase
{
  private UserTableWrapper $table;

  protected function setUp(): void
  {
    $this->table = new UserTableWrapper();
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

  public function testUpdateWithoutId(): void
  {
    $this->table->insert(['name' => 'John', 'email' => 'john@example.com']);
    $this->table->insert(['name' => 'Jane', 'email' => 'jane@example.com']);

    $result = $this->table->update(1, ['name' => 'Updated']);
    $this->assertEquals([], $result);
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
