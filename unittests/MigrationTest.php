<?php

namespace UnitTests;

use PHPUnit\Framework\TestCase;
use Migrations\TableMigrations;
use Controllers\BaseController;

/**
 * Test api methods from here
 */
class MigrationTest extends TestCase
{
    public function testMigration()
    {
        $connection = (new BaseController())->getConnection();
        $this->assertNotNull($connection);
    }
}
