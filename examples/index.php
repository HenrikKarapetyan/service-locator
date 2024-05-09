<?php

require '../vendor/autoload.php';

use henrik\sl\DependencyInjector;
use henrik\sl\InjectorModes;

$injector = DependencyInjector::instance();
$injector->setMode(InjectorModes::CONFIG_FILE);
$services = require 'services.php';
$injector->load($services);

/** @var callable $val */
$val = $injector->get('simple2');

// $injector->dumpContainer();
var_dump($val());

