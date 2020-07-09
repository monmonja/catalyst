<?php
namespace Tests;

use App\CSVImporter;
use Exception;
use PHPUnit\Framework\TestCase;

class CSVImporterTest extends TestCase   {

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

}