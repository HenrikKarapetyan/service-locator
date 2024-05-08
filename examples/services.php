<?php

use henrik\sl\SampleClasses\SampleClassA;
use henrik\sl\SampleClasses\SampleClassB;
use henrik\sl\SampleClasses\SampleClassC;
use henrik\sl\SampleClasses\SampleClassD;
use henrik\sl\ServiceScope;

return [
    ServiceScope::FACTORY->value => [
        [SampleClassA::class],
    ],
    ServiceScope::PROTOTYPE->value => [
        [SampleClassB::class],
    ],
    ServiceScope::SINGLETON->value => [
        [SampleClassC::class],
        [SampleClassD::class],
    ],
    ServiceScope::PARAM->value => [
        ['simple' => 'simpleValue'],
    ],
    ServiceScope::ALIAS->value => [
        ['simpleAlias' => SampleClassA::class],
    ],
];