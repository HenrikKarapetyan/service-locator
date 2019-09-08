<?php


namespace henrik\sl;


use henrik\container\Container;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\providers\Provider;
use henrik\container\ContainerModes;

class ServicesContainer extends Container
{
    /**
     * ServicesContainer constructor.
     */
    public function __construct()
    {
        $this->change_mode(ContainerModes::SINGLE_VALUE_MODE);
    }

    /**
     * @param $id
     * @param Provider $provider
     * @throws \Exception
     */
    public function add($id, Provider $provider)
    {
        $this->set($id, $provider);
    }

    /**
     * @param $id
     * @return mixed
     * @throws ServiceNotFoundException
     * @throws \Exception
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return parent::get($id)->provide();
        }
        throw new ServiceNotFoundException(sprintf('Service "%s" not found', $id));
    }

}