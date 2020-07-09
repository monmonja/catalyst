<?php
namespace App;

use App\Models\User;
use Exception;

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
   */
  public function processCSV ($csv) {
    // extract the first row for the column name with mapHeaderColumns
    // $headerColumns = $this->mapHeaderColumns([]);

    $dataModels = [];
    // loop with parseRow, add all to $dataModels
    return $dataModels;
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
   * @return User
   * @throws Exception
   *   error when there are errors with the data
   */
  public function parseRow (array $row) {
    $user = new User();
    // check with header map and set the field with the user model
    return $user;
  }


  /**
   * Insert the data to the database
   * @param array $data
   */
  public function insertAllToDB (array $data) {
    // open connection
    // prepare statement
    // insert db
    // close connection
  }
}