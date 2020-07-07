<?php
namespace App;

use App\Models\User;
use PHPUnit\Exception;

class CSVImporter {
  /**
   * Import csv from path
   * @param string $path
   * @throws Exception
   *   that file doesnt exist | invalid emails
   * @return array
   *  list of users
   */
  public function readFileFromPath ($path) {
    // check if file exists
      // get data from the file

      // extract the first row for the column name with mapHeaderColumns
      // $headerColumns = $this->mapHeaderColumns([]);

      $dataModels = [];
      // loop with parseRow, add all to $dataModels
      return $dataModels;
    // throw error when file doest exist or when there is an invalid email
  }

  /**
   *  Map the first row of the csv file to this array
   *  @param $row
   *    pass in the first row
   *  @return array
   *    map of the columns
   */
  public function mapHeaderColumns (array $row) {
    // manually create a map
    return [];
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