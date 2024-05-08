<?php

namespace henrik\sl\SampleClasses;

use henrik\sl\ServiceScopeInterfaces\FactoryAwareInterface;

class SampleClassD implements FactoryAwareInterface
{
    /**
     * @var SampleClassA
     */
    private SampleClassA $aObj;
    /**
     * @var SampleClassB
     */
    private SampleClassB $bObj;
    /**
     * @var SampleClassC
     */
    private SampleClassC $cObj;

    public function __construct(SampleClassA $aObj, SampleClassB $bObj, SampleClassC $cObj)
    {
        var_dump("created\t" . self::class);

        $this->aObj = $aObj;
        $this->bObj = $bObj;
        $this->cObj = $cObj;
    }
}