<?php


namespace henrik\sl\providers;

/**
 * Class FactoryProvider
 * @package henrik\sl\providers
 */
class FactoryProvider extends ObjectProvider
{

    /**
     * @return mixed|null
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     * @throws \henrik\sl\exceptions\ServiceConfigurationException
     * @throws \henrik\sl\exceptions\ServiceNotFoundException
     */
    function provide()
    {
        return $this->injector->instantiate($this->value, $this->params);
    }
}