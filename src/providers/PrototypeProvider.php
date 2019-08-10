<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:03 PM
 */

namespace henrik\sl\providers;


use henrik\sl\Instantiator;

class PrototypeProvider extends ObjectProvider
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
        if ($this->instance === null) {
            $this->instance = Instantiator::instantiate($this->value, $this->params);
        }
        return clone $this->instance;
    }
}