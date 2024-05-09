<?php

require '../vendor/autoload.php';

use henrik\sl\Injector;
use henrik\sl\InjectorModes;
use henrik\sl\SampleClasses\SampleClassD;

$injector = Injector::instance();
$injector->setMode(InjectorModes::CONFIG_FILE);
$services = require 'services.php';
$injector->load($services);

/** @var SampleClassD $val */
$val = $injector->get(SampleClassD::class);

$injector->dumpContainer();
