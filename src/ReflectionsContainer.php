<?php

declare(strict_types=1);

namespace henrik\sl;

use Exception;
use henrik\container\Container;
use henrik\container\ContainerModes;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\UndefinedModeException;
use ReflectionClass;

class ReflectionsContainer extends Container
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
     * @param $klass
     *
     * @throws IdAlreadyExistsException
     * @throws Exception
     *
     * @return mixed
     */
    public function getReflectionClass($klass): mixed
    {
        if (!$this->has($klass)) {
            $this->set($klass, new ReflectionClass($klass));
        }

        return $this->get($klass);
    }
}