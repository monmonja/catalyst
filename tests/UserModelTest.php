<?php
namespace Tests;

use App\DbConnection;
use App\Models\User;
use Exception;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase   {

  // make sure user table doesnt exists
  public function testDropTableNotExist () {
    try {
      $dbConnection = new DbConnection();
      $dbConnection->connect('localhost', 'catalyst', 'almond_local', 'yN44dAITJ97dL9JGq8bQ');
      $dbConnection->getConnection()->exec('drop table if exists users');
      $dbConnection->getConnection()->query('select * from users');

      $this->assertTrue(false);
    } catch (Exception $e) {
      $this->assertTrue(true);

    }
  }


  public function testCreateDbTable () {
    try {
      $dbConnection = new DbConnection();
      $dbConnection->connect('localhost', 'catalyst', 'almond_local', 'yN44dAITJ97dL9JGq8bQ');
      $user = new User();
      $user->createDbTable($dbConnection->getConnection());
      $this->assertTrue(true);
    } catch (Exception $e) {
      die($e->getMessage());
      $this->assertTrue(false);
    }
  }

}