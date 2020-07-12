<?php
namespace App;

use App\Models\User;
use Exception;
use PDO;

class CSVImporter {
  /**
   * Import csv from path
   * @param string $path
   * @throws Exception
   *   that file doesnt exist | invalid emails
   * @return array
   *  list of users
   */
  public function getCSVFromPath ($path) {
    // if the passed file has / at start then assume its a full path
    if (strpos('/', $path) !== 0) {
      $path = __DIR__ . '/../' . $path;
    }

    // check if file exists
    if (file_exists($path)) {
      // get csv from the file
      return array_map('str_getcsv', file($path));
    } else {
      // throw error when file doest exist
      throw new Exception('File doesnt exist');
    }
  }

  /**
   * Check if the csv is totally valid
   * @param $csv
   * @return boolean
   * @throws Exception
   */
  public function validateCSV ($csv) {
    $headerColumns = $this->mapHeaderColumns(array_shift($csv));
    // throw there is an invalid email
    $data = array_map(function ($row) use ($headerColumns) {
      return trim($row[$headerColumns['email']]);
    }, $csv);
    $invalidEmails = array_filter($data, function ($email) {
      return !filter_var($email, FILTER_VALIDATE_EMAIL);
    });
    if (empty($invalidEmails)) {
      return true;
    } else{
      throw new Exception('Invalid emails/: ' . implode(',', $invalidEmails));
    }
  }

  /**
   * Check if the csv is totally valid
   * @param $csv
   * @return array
   * @throws Exception
   */
  public function processCSV ($csv) {
    if ($this->validateCSV($csv)) {
      // extract the first row for the column name with mapHeaderColumns
      $headerColumns = $this->mapHeaderColumns(array_shift($csv));

      $dataModels = [];
      // loop with parseRow, add all to $dataModels
      foreach ($csv as $row) {
        array_push($dataModels, $this->parseRow($row, $headerColumns));
      }
      return $dataModels;
    }
    return [];
  }

  /**
   *  Map the first row of the csv file to this array
   *  @param $row
   *    pass in the first row
   *  @return array
   *    map of the columns
   */
  public function mapHeaderColumns (array $row) {
    $row = array_map(function ($item) {
      return trim($item);
    }, $row);
    return array_flip($row);
  }

  /**
   * Parse every row of the csv file
   * @param array $row
   * @param array $headerColumns
   * @return User
   */
  public function parseRow (array $row, array $headerColumns) {
    // check with header map and set the field with the user model
    $user = new User();
    if (isset($headerColumns['email'])) {
      $user->setEmail($row[$headerColumns['email']]);
    }

    if (isset($headerColumns['name'])) {
      $user->setName($row[$headerColumns['name']]);
    }

    if (isset($headerColumns['surname'])) {
      $user->setSurname($row[$headerColumns['surname']]);
    }
    return $user;
  }

  /**
   * Insert the data to the database
   * @param PDO $connection
   * @param array $data
   * @param bool $dryRun
   * @throws Exception
   */
  public function insertAllToDB (PDO $connection, array $data, bool $dryRun = false) {
    if (User::checkIfTableExist($connection)) {
      try {
        $connection->beginTransaction();
        // prepare statement
        $statement = $connection->prepare('INSERT INTO users (name, surname, email) VALUES (?, ?, ?)');

        /** @var User $row */
        // insert db
        foreach ($data as $row) {
          $statement->bindValue(1, $row->getCleanName(), PDO::PARAM_STR);
          $statement->bindValue(2, $row->getCleanSurname(), PDO::PARAM_STR);
          $statement->bindValue(3, $row->getCleanEmail(), PDO::PARAM_STR);
          $statement->execute();
        }

        if (!$dryRun) {
          $connection->commit();
        }
      } catch (Exception $e) {
        throw new Exception($e->getMessage());
      }

    }
  }
}