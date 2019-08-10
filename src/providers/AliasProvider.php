<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:13 PM
 */

namespace henrik\sl\providers;


use henrik\sl\ServiceLocator;

class AliasProvider extends ServiceProvider
{
    /**
     * @return mixed
     * @throws \Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     */
    function provide()
    {
        return ServiceLocator::get($this->value);
    }
}