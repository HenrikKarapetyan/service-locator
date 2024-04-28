<?php

namespace henrik\sl\SampleClasses;

class SampleClassC
{
    /**
     * @var SampleClassA
     */
    private SampleClassA $aObj;
    /**
     * @var SampleClassB
     */
    private SampleClassB $bObj;

    public function __construct(SampleClassA $aObj, SampleClassB $bObj)
    {
        var_dump("created\t" . self::class);

        $this->aObj = $aObj;
        $this->bObj = $bObj;
    }
}