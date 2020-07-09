<?php
namespace Tests;

use App\DbConnection;
use Exception;
use PHPUnit\Framework\TestCase;

class DbConnectionTest extends AppBaseTestCase   {

  public function testConnectPass () {
    try {
      $dbConnection = $this->getDbConnection();
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testConnectFail () {
    try {
      $dbConnection = new DbConnection();
      $dbConnection->connect('localhost', 'catalyst', 'someone', 'password');
      $this->assertTrue(false);
    } catch (Exception $e) {
      $this->assertTrue(true);
    }
  }
}