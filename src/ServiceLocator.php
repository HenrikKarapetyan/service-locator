<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 2:09 PM
 */

namespace henrik\sl;

use henrik\container\Container;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\exceptions\UnknownScopeException;
use henrik\sl\helpers\ArrayConfigParser;
use henrik\sl\providers\Provider;

/**
 * Class ServiceLocator
 * @package henrik\sl
 */
class ServiceLocator
{
    /**
     * @var Container
     */
    private static $service_container;

    /**
     * @param $id
     * @param Provider $provider
     * @throws \Exception
     */
    public static function set($id, Provider $provider)
    {
        static::getServiceContainer()->set($id, $provider);
    }

    /**
     *
     */
    public static function deleteAll()
    {
        static::getServiceContainer()->deleteAll();
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        static::getServiceContainer()->delete($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function has($id)
    {
        return static::getServiceContainer()->has($id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws ServiceNotFoundException
     * @throws \Exception
     */
    public static function get($id)
    {
        if (static::getServiceContainer()->has($id)) {
            return static::getServiceContainer()->get($id)->provide();
        }
        throw new ServiceNotFoundException(sprintf('Service "%s" not found', $id));
    }

    /**
     * @param $services
     * @throws UnknownScopeException
     * @throws \henrik\container\exceptions\IdAlreadyExistsException
     * @throws \henrik\container\exceptions\TypeException
     */
    public static function load($services)
    {
        foreach ($services as $scope => $service_items) {
            if (in_array($scope, ServiceScope::SCOPES)) {
                foreach ($service_items as $item) {
                    $parsed_item = ArrayConfigParser::parse($item);
                    $provider = '\\henrik\\sl\\providers\\' . ucfirst($scope . 'Provider');
                    $klass = $parsed_item['class'];
                    $params = [];
                    if (isset($parsed_item['params'])) {
                        $params = $parsed_item['params'];
                    }
                    $provider_inst = new $provider($klass, $params);
                    static::getServiceContainer()->set($parsed_item['id'], $provider_inst);
                }
            } else {
                throw new UnknownScopeException(sprintf('Unknown  scope "%s"', $scope));
            }
        }
    }

    /**
     * @return Container
     */
    private static function getServiceContainer()
    {
        if (static::$service_container === null) {
            static::$service_container = new Container();
        }
        return static::$service_container;
    }
}