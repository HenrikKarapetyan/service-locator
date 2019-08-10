<?php


class  A extends \henrik\sl\SLComponent
{

}

class B extends \henrik\sl\SLComponent
{

}


return [
    \henrik\sl\ServiceScope::SINGLETON => [
        ['A' => A::class]
    ],
    \henrik\sl\ServiceScope::PROTOTYPE => [
        ['B' => B::class]
    ]
];