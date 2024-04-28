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
     * @return mixed|null
     *@throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws ServiceNotFoundException|IdAlreadyExistsException
     *
     * @throws ServiceConfigurationException
     */
    public function provide(): mixed
    {
        return $this->injector->instantiate($this->value, $this->params);
    }
}