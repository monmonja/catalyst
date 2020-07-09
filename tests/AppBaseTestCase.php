<?php

namespace Tests;


use App\DbConnection;
use PHPUnit\Framework\TestCase;

class AppBaseTestCase extends TestCase
{
  protected function getDbConnection () {
    $dbConnection = new DbConnection();
    $dbConnection->connect('localhost', 'catalyst', 'almond_local', 'yN44dAITJ97dL9JGq8bQ');
    return $dbConnection;
  }
}
