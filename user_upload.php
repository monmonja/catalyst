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
    ->setDescription('This will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won\'t be altered')
    ->setValidation(function () use ($getOpt) {
      return $getOpt->getOption('u') && $getOpt->getOption('p') ;
    }, 'File is required'),

  Option::create(null, 'create_table', GetOpt::NO_ARGUMENT)
    ->setDescription('This will cause the PostgreSQL users table to be built (and no further action will be taken)')
    ->setValidation(function () use ($getOpt) {
      return $getOpt->getOption('h') && $getOpt->getOption('u') && $getOpt->getOption('p') ;
    }, 'DB connection details are required'),

  Option::create('u', null, GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('PostgreSQL username')
    ->setValidation(function () use ($getOpt) {
      return $getOpt->getOption('h') && $getOpt->getOption('p') ;
    }, 'Host and password are required'),

  Option::create('p', null, GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('PostgreSQL password')
    ->setValidation(function () use ($getOpt) {
      return $getOpt->getOption('u') && $getOpt->getOption('h') ;
    }, 'Host and password are required'),

  Option::create('h', null, GetOpt::OPTIONAL_ARGUMENT)
    ->setDescription('PostgreSQL host')
    ->setValidation(function () use ($getOpt) {
      return $getOpt->getOption('u') && $getOpt->getOption('p') ;
    }, 'Username and password are required'),

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
}