<?php

namespace App;

use PDO;
use Exception;

class DbConnection {
  protected $connection;

  /**
   * Connect to database
   * @param $host
   * @param $db
   * @param $username
   * @param $password
   *
   * @throws Exception
   */
  public function connect ($host, $db, $username, $password) {
    $this->connection = new PDO('pgsql:' . implode(';', [
        'host=' . $host,
        'dbname=' . $db
      ]), $username, $password,
      [ PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ]);
  }

  public function getConnection () {
    if (is_null($this->connection)) {
      throw new Exception('No connection');
    }
    return $this->connection;
  }
}