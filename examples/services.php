<?php

use henrik\sl\SampleClasses\SampleClassA;
use henrik\sl\ServiceScope;

return [
    ServiceScope::PARAM->value => [
        ['simple' => 'simpleValue'],
    ],
    ServiceScope::ALIAS->value => [
        ['simpleAlias' => SampleClassA::class],
    ],
];