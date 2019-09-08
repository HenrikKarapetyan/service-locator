<?php


namespace henrik\sl;


use henrik\container\Container;
use henrik\container\ContainerModes;

class ReflectionsContainer extends Container
{
    /**
     * ReflectionsContainer constructor.
     */
    public function __construct()
    {
        $this->change_mode(ContainerModes::SINGLE_VALUE_MODE);
    }

    /**
     * @param $klass
     * @return mixed
     * @throws \ReflectionException
     * @throws \henrik\container\exceptions\IdAlreadyExistsException
     * @throws \henrik\container\exceptions\TypeException
     * @throws \Exception
     */
    public function getReflectionClass($klass)
    {
        if (!$this->has($klass)) {
            $this->set($klass, new \ReflectionClass($klass));
        }
        return $this->get($klass);
    }
}