<?php

namespace henrik\sl\SampleClasses;

use henrik\sl\Attributes\AsPrototype;

#[AsPrototype]
class SampleClassA
{
    public function __construct()
    {
        var_dump("created \t" . self::class);
    }
}
