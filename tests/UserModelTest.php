<?php
namespace Tests;

use App\DbConnection;
use App\Models\User;
use Exception;
use PHPUnit\Framework\TestCase;

class UserModelTest extends AppBaseTestCase   {

  // make sure user table doesnt exists
  public function testDropTableNotExist () {
    try {
      $dbConnection = $this->getDbConnection();
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
      $dbConnection = $this->getDbConnection();
      User::createDbTable($dbConnection->getConnection());
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testTableExist () {
    try {
      $dbConnection = new DbConnection();
      $dbConnection = $this->getDbConnection();
      if (User::checkIfTableExist($dbConnection->getConnection())) {
        $this->assertTrue(true);
      }
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testTableDoesntExist () {
    try {
      $dbConnection = $this->getDbConnection();
      $dbConnection->getConnection()->exec('drop table if exists users');
      User::checkIfTableExist($dbConnection->getConnection());
    } catch (Exception $e) {
      $this->assertTrue(true);
    }
  }

}