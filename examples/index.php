<?php


require "../vendor/autoload.php";

use henrik\sl\Injector;

$services = require "services.php";

$injector  = new Injector();
$injector->load($services);


var_dump($injector->get(D::class));
