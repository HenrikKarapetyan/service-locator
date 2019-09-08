<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:02 PM
 */

namespace henrik\sl\providers;

/**
 * Class SingletonProvider
 * @package henrik\sl\providers
 */
class SingletonProvider extends ObjectProvider
{
    /**
     * @return mixed|object
     * @throws \Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     * @throws \henrik\sl\exceptions\ServiceConfigurationException
     * @throws \henrik\sl\exceptions\ServiceNotFoundException
     */
    function provide()
    {
        if ($this->instance === null)
            $this->instance = $this->injector->instantiate($this->value, $this->params);
        return $this->instance;
    }
}