<?php

require '../vendor/autoload.php';

use henrik\sl\DependencyInjector;
use henrik\sl\InjectorModes;
use henrik\sl\SampleClasses\SampleClassA;

$injector = DependencyInjector::instance();
$injector->setMode(InjectorModes::AUTO_REGISTER);
$services = require 'services.php';
$injector->load($services);

/** @var callable $val */
$val = $injector->get(SampleClassA::class);

$injector->dumpContainer();
var_dump($val);

