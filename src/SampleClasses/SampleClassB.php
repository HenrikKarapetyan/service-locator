<?php

namespace henrik\sl\SampleClasses;

class SampleClassB
{
    /**
     * @var SampleClassA
     */
    private SampleClassA $anyValue;

    public function __construct(SampleClassA $anyValue)
    {
        var_dump("created\t" . self::class);
        $this->anyValue = $anyValue;
    }
}