<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:02 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

use Exception;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\ServiceConfigurationException;

/**
 * Class SingletonProvider.
 */
class SingletonProvider extends ObjectProvider
{
    /**
     * @throws ServiceConfigurationException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws Exception
     * @throws ServiceNotFoundException
     *
     * @return object
     */
    public function provide(): object
    {
        if ($this->instance === null) {
            $this->instance = $this->injector->instantiate((string) $this->definition->getClass(), $this->definition->getParams());
        }

        return $this->instance;
    }
}