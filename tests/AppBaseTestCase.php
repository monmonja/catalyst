<?php

namespace Tests;


use App\DbConnection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class AppBaseTestCase extends TestCase
{
  protected function getDbConnection () {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/../.env');

    $dbConnection = new DbConnection();
    $dbConnection->connect($_ENV['TEST_DB_HOST'], $_ENV['DB_NAME'], $_ENV['TEST_DB_USERNAME'], $_ENV['TEST_DB_PASSWORD']);
    return $dbConnection;
  }
}
