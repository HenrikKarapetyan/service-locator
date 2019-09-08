<?php


use henrik\sl\ServiceScope;
use henrik\sl\SLComponent;

class  A
{
    public function __construct()
    {
        var_dump("created\t" . self::class);
    }
}

class B
{

    /**
     * @var A
     */
    private $sadaasdasd;

    public function __construct(A $sadaasdasd)
    {
        var_dump("created\t" . self::class);
        $this->sadaasdasd = $sadaasdasd;
    }
}

class C
{

    /**
     * @var A
     */
    private $aObj;
    /**
     * @var B
     */
    private $bObj;

    public function __construct(A $aObj, B $bObj)
    {
        $this->aObj = $aObj;
        $this->bObj = $bObj;
    }
}


class D
{
    /**
     * @var A
     */
    private $aObj;
    /**
     * @var B
     */
    private $bObj;
    /**
     * @var C
     */
    private $cObj;

    public function __construct(A $aObj, B $bObj, C $cObj)
    {
        $this->aObj = $aObj;
        $this->bObj = $bObj;
        $this->cObj = $cObj;
    }
}

return [
    ServiceScope::FACTORY => [
        ['A' => A::class]
    ],
    ServiceScope::PROTOTYPE => [
        ['B' => B::class]
    ],
    ServiceScope::SINGLETON => [
        [C::class],
        [D::class]
    ]


];