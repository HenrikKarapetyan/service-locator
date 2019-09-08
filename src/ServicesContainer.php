<?php


namespace henrik\sl;


use henrik\container\Container;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\providers\Provider;

class ServicesContainer extends Container
{
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