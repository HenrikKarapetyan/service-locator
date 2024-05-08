<?php

require '../vendor/autoload.php';

use henrik\sl\Injector;
use henrik\sl\InjectorModes;
use henrik\sl\SampleClasses\SampleClassD;

$injector = Injector::instance();
$injector->setMode(InjectorModes::AUTO_REGISTER);

/** @var SampleClassD $val */
$val = $injector->get(SampleClassD::class);

$injector->dumpContainer();
