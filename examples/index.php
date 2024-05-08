<?php

require '../vendor/autoload.php';

use henrik\sl\Injector;

$services = require 'services.php';

$injector = Injector::instance();
$injector->load($services);

$val  = $injector->get('simpleAlias');
$val2 = $injector->get('simpleAlias');

var_dump($val === $val2);
