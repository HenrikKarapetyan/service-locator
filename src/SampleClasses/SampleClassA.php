<?php

namespace henrik\sl\SampleClasses;

class SampleClassA
{
    public function __construct()
    {
        var_dump("created\t" . self::class);
    }
}
