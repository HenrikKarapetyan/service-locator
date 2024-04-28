<?php


require "../vendor/autoload.php";

use henrik\sl\Injector;

$services = require "services.php";

$injector  = Injector::instance();
$injector->load($services);


$injector->get(\henrik\sl\SampleClasses\SampleClassD::class);
