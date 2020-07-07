<?php
namespace Tests;

use App\DbConnection;
use Exception;
use PHPUnit\Framework\TestCase;

class DbConnectionTest extends TestCase   {

  public function testConnectPass () {
    try {
      $dbConnection = new DbConnection();
      $dbConnection->connect('localhost', 'catalyst', 'almond_local', 'yN44dAITJ97dL9JGq8bQ');
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