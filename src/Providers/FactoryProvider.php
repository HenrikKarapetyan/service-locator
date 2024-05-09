<?php

declare(strict_types=1);

namespace henrik\sl\Providers;

use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\ServiceConfigurationException;

/**
 * Class FactoryProvider.
 */
class FactoryProvider extends ObjectProvider
{
    /**
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws ServiceNotFoundException|IdAlreadyExistsException
     * @throws ServiceConfigurationException
     *
     * @return object
     */
    public function provide(): object
    {
        return $this->injector->instantiate($this->definition);
    }
}