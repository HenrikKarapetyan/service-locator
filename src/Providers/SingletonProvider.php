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
     * @return mixed|object
     * @throws ServiceConfigurationException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws Exception
     *
     * @throws ServiceNotFoundException
     */
    public function provide(): mixed
    {
        if ($this->instance === null) {
            $this->instance = $this->injector->instantiate($this->value, $this->params);
        }

        return $this->instance;
    }
}