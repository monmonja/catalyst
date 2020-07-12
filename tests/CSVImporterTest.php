<?php
namespace Tests;

use App\CSVImporter;
use App\Models\User;
use Exception;

class CSVImporterTest extends AppBaseTestCase   {

  public function testGetCSVFromPathInvalidPath () {
    try {
      $importer = new CSVImporter();
      $importer->getCSVFromPath('non-existing.csv');
      $this->assertTrue(false);
    } catch (Exception $e) {
      $this->assertTrue(true);
    }
  }

  public function testGetCSVFromPathValidPath () {
    try {
      $importer = new CSVImporter();
      $importer->getCSVFromPath('users.csv');
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testGetCSVFromPathArrayCheck () {
    try {
      $importer = new CSVImporter();
      $csv = $importer->getCSVFromPath('users.csv');
      $this->assertEquals(12, sizeof($csv));
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testMapHeaderColumns () {
    try {
      $importer = new CSVImporter();
      $headers = $importer->mapHeaderColumns(
        ['name', '  surname', 'email  ']
      );
      $this->assertEquals($headers, [
        'name' => 0,
        'surname' => 1,
        'email' => 2
      ]);
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testValidateCSVInvalidEmail () {
    try {
      $importer = new CSVImporter();
      $importer->validateCSV([
        ['name', 'surname', 'email'],
        ['Edward ','JIKES','edward@jikes@com.au'],
      ]);
      $this->assertTrue(false);
    } catch (Exception $e) {
      $this->assertTrue(true);
    }
  }

  public function testValidateCSVValid () {
    try {
      $importer = new CSVImporter();
      $importer->validateCSV([
        ['name', 'surname', 'email'],
        ['kevin', 'Ruley','kevin.ruley@gmail.com'],
      ]);
      $this->assertTrue(true) ;
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testParseRow () {
    try {
      $headers = [
        'name' => 0,
        'surname' => 1,
        'email' => 2
      ];
      $importer = new CSVImporter();
      $user = $importer->parseRow(['kevin', 'Ruley','kevin.ruley@gmail.com'], $headers);

      $this->assertEquals($user->getCleanEmail(), 'kevin.ruley@gmail.com') ;
      $this->assertEquals($user->getCleanName(), 'Kevin') ;
      $this->assertEquals($user->getCleanSurname(), 'Ruley') ;
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testProcessCSV () {
    try {
      $importer = new CSVImporter();
      $rows = $importer->processCSV([
        ['name', 'surname', 'email'],
        ['kevin', 'Ruley','kevin.ruley@gmail.com'],
      ]);

      $this->assertEquals(1, sizeof($rows));
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testInsertAllToDBDryRun () {
    try {
      $dbConnection = $this->getDbConnection();
      $dbConnection->getConnection()->exec('drop table if exists users');
      User::createDbTable($dbConnection->getConnection());
      $importer = new CSVImporter();
      $rows = $importer->processCSV([
        ['name', 'surname', 'email'],
        ['kevin', 'Ruley','kevin.ruley@gmail.com'],
      ]);
      $importer->insertAllToDB($dbConnection->getConnection(),  $rows, true);
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

  public function testInsertAllToDInsert () {
    try {
      $dbConnection = $this->getDbConnection();
      $importer = new CSVImporter();
      $rows = $importer->processCSV([
        ['name', 'surname', 'email'],
        ['kevin', 'Ruley','kevin.ruley@gmail.com'],
      ]);
      $importer->insertAllToDB($dbConnection->getConnection(),  $rows, false);

      $statement = $dbConnection->getConnection()->query("select count(*) from users");
      $dbRows = $statement->fetchColumn();
      $this->assertEquals(1, $dbRows);
    } catch (Exception $e) {
      $this->assertTrue(false);
    }
  }

}