<?php

use App\DbConnection;
use App\Models\User;
use GetOpt\ArgumentException;
use GetOpt\GetOpt;
use GetOpt\Option;

require_once __DIR__ . '/vendor/autoload.php';


$getOpt = new GetOpt();

// define common options
$getOpt->addOptions([
  Option::create(null, 'file', GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('This is the name of the CSV to be parsed'),

  Option::create(null, 'dry_run', GetOpt::NO_ARGUMENT)
    ->setDescription('This will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won\'t be altered'),

  Option::create(null, 'create_table', GetOpt::NO_ARGUMENT)
    ->setDescription('This will cause the PostgreSQL users table to be built (and no further action will be taken)'),

  Option::create('u', null, GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('PostgreSQL username'),

  Option::create('p', null, GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('PostgreSQL password'),

  Option::create('h', null, GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('PostgreSQL host'),

  Option::create(null, 'help', GetOpt::NO_ARGUMENT)
    ->setDescription('Which will output the above list of directives with details.'),

]);

function showError ($message, $getOpt, $addHelp = false) {
  file_put_contents('php://stderr', $message . PHP_EOL);
  if ($addHelp) {
    echo PHP_EOL . $getOpt->getHelpText();
  }
  exit;
}

try {
  $getOpt->process();
} catch (ArgumentException $exception) {
  showError($exception->getMessage(), $getOpt, true);
}

$databaseName = "catalyst";
// show help and quit
if ($getOpt->getOption('help')) {
  echo $getOpt->getHelpText();
  exit;
} elseif ($getOpt->getOption('create_table')) {
  $host = $getOpt->getOption('h');
  $username = $getOpt->getOption('u');
  $password = $getOpt->getOption('p');
  if (is_null($getOpt->getOption('h')) ||
    is_null($getOpt->getOption('u')) ||
    is_null($getOpt->getOption('p'))
  ) {
    showError("Host, username and password are required", $getOpt, true);
  }

  $dbConnection = new DbConnection();
  try {
    $dbConnection->connect($host, $databaseName, $username, $password);
    $user = new User();
    $user->createDbTable($dbConnection->getConnection());
    echo 'Create table done' . PHP_EOL ;
    exit;
  } catch (Exception $exception) {
    showError("Error in create table " . $exception->getMessage(), $getOpt);
    exit;
  }
} else {
  // do import
}