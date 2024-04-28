<?php

use henrik\sl\ServiceScope;


return [
    ServiceScope::FACTORY->value => [
        [\henrik\sl\SampleClasses\SampleClassA::class]
    ],
    ServiceScope::PROTOTYPE->value => [
        [\henrik\sl\SampleClasses\SampleClassB::class]
    ],
    ServiceScope::SINGLETON->value => [
        [\henrik\sl\SampleClasses\SampleClassC::class],
        [\henrik\sl\SampleClasses\SampleClassD::class]
    ]
];