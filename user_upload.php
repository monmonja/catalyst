<?php

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

try {
  $getOpt->process();
} catch (ArgumentException $exception) {
  file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL);
  echo PHP_EOL . $getOpt->getHelpText();
  exit;
}

// show help and quit
if ($getOpt->getOption('help')) {
  echo $getOpt->getHelpText();
  exit;
} elseif ($getOpt->getOption('create_table')) {
  $dbHost = $getOpt->getOption('h');
  $dbUsername = $getOpt->getOption('u');
  $dbPassword = $getOpt->getOption('p');
  echo $dbHost;
  exit;
}