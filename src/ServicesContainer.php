<?php

declare(strict_types=1);

namespace henrik\sl;

use henrik\container\Container;
use henrik\container\ContainerModes;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\container\exceptions\UndefinedModeException;
use henrik\sl\Providers\ProviderInterface;

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
     * @param string            $id
     * @param ProviderInterface $provider
     *
     * @throws IdAlreadyExistsException
     */
    public function add(string $id, ProviderInterface $provider): void
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
            $containerServedData = parent::get($id);
            if ($containerServedData instanceof ProviderInterface) {
                return $containerServedData->provide();
            }

            return $containerServedData;
        }

        return null;
    }
}