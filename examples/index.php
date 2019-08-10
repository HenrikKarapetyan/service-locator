<?php

require "vendor/autoload.php";

$services = require "services.php";

\henrik\sl\ServiceLocator::load($services);


var_dump(\henrik\sl\ServiceLocator::get('A'));
