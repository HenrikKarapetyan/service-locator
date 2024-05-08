<?php

declare(strict_types=1);

namespace henrik\sl;

use henrik\container\Container;
use henrik\container\ContainerModes;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\container\exceptions\UndefinedModeException;
use ReflectionClass;

class RCContainer extends Container
{
    /**
     * ReflectionsContainer constructor.
     *
     * @throws UndefinedModeException
     */
    public function __construct()
    {
        $this->changeMode(ContainerModes::SINGLE_VALUE_MODE);
    }

    /**
     * @param string $klass
     *
     * @throws IdAlreadyExistsException
     * @throws ServiceNotFoundException
     *
     * @return mixed
     */
    public function getReflectionClass(string $klass): mixed
    {
        if (!$this->has($klass) && class_exists($klass)) {
            $this->set($klass, new ReflectionClass($klass));
        }

        return $this->get($klass);
    }
}