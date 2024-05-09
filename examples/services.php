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
    ServiceScope::SINGLETON->value => [
        [SampleClassB::class],
        [SampleClassC::class],
        [SampleClassD::class],
    ],
    ServiceScope::PARAM->value => [
        ['simple' => 'simpleValue'],
        ['simple2' => function () {
            return 'ok';
        }],
    ],
    ServiceScope::ALIAS->value => [
        ['simpleAlias' => SampleClassA::class],
    ],
];