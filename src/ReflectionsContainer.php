<?php


namespace henrik\sl;


use henrik\container\Container;

class ReflectionsContainer extends Container
{
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