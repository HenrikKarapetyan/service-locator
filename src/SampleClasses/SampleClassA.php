<?php

namespace henrik\sl\SampleClasses;

use henrik\sl\Attributes\AsPrototype;

#[AsPrototype]
class SampleClassA
{
    public function __construct(string $uri, SampleClassX $sampleClassX)
    {
        echo $uri;
        var_dump("created \t" . self::class);
    }
}
