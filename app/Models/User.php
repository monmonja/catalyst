<?php

namespace App\Models;

use Exception;

class User {
  /** @var string $name */
  protected $name;
  /** @var string $surname */
  protected $surname;
  /** @var string $email */
  protected $email;


  public function setEmail ($email) {
    $this->email = $email;
  }

  public function setName ($name) {
    $this->name = $name;
  }

  public function setSurname ($surname) {
    $this->surname = $surname;
  }

  /**
   *  Emails need to be set to be lower case
   */
  public function getCleanEmail () {
     return strtolower(trim($this->email));
  }

  /**
   *  Name and surname field should be set to be capitalised e.g. from “john” to “John”
   */
  public function getCleanName () {
     return ucfirst(strtolower(trim($this->name)));
  }

  /**
   *  Name and surname field should be set to be capitalised e.g. from “john” to “John”
   */
  public function getCleanSurname () {
     return ucfirst(strtolower(trim($this->surname)));
  }

  /**
   * Insert data to db
   * @todo pass in connection or statement
   */
  public function executeDbInsert () {
    return true;
  }

  /**
   * Create table on the db
   * @param $connection
   * @todo pass in connection or statement
   *
   * The PostgreSQL table should contain at least these fields:
   *  - name
   *  - surname
   *  - email (email should be set to a UNIQUE index).
   */
  public static function createDbTable (\PDO $connection)
  {
    // Script will iterate through the CSV rows and insert each record into a dedicated PostgreSQL
    //database into the table “users”
    $table = 'users';
    $sql = /** @lang PostgreSQL */ <<<SQL
CREATE TABLE IF NOT EXISTS $table (
    id SERIAL PRIMARY KEY,
    name varchar(45) NOT NULL,
    surname varchar(45) NOT NULL,
    email varchar(45) NOT NULL UNIQUE
);
SQL;

    // The users database table will need to be created/rebuilt as part of the PHP script. This will be
    // defined as a Command Line directive below
    // create table
    $connection->exec($sql);
  }

  /**
   * Check if the user table exists
   * @param \PDO $connection
   * @return bool
   * @throws Exception
   */
  public static function checkIfTableExist (\PDO $connection) {
    try {
      $connection->query('SELECT 1 FROM users LIMIT 1');
      return true;
    } catch (Exception $e) {
      throw new Exception('Users table doesnt exist. Please run --create_table');
    }
  }

}