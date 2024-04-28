<?php

declare(strict_types=1);

namespace henrik\sl;

use henrik\container\Container;
use henrik\container\ContainerModes;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\container\exceptions\UndefinedModeException;
use henrik\sl\Providers\Provider;

class ServicesContainer extends Container
{
    /**
     * ServicesContainer constructor.
     *
     * @throws UndefinedModeException
     */
    public function __construct()
    {
        $this->changeMode(ContainerModes::SINGLE_VALUE_MODE);
    }

    /**
     * @param string   $id
     * @param Provider $provider
     *
     * @throws IdAlreadyExistsException
     */
    public function add(string $id, Provider $provider): void
    {
        $this->set($id, $provider);
    }

    /**
     * @param string $id
     *
     * @throws ServiceNotFoundException
     *
     * @return mixed
     */
    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            return parent::get($id)->provide();
        }

        throw new ServiceNotFoundException($id);
    }
}