<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/4/2018
 * Time: 9:11 AM
 */

namespace henrik\sl;


use henrik\component\ComponentInterface;
use henrik\sl\exceptions\MustImplementComponentException;
use henrik\sl\exceptions\ServiceConfigurationException;
use henrik\sl\exceptions\ServiceNotFoundException;

/**
 * Class Instantiator
 * @package henrik\sl
 */
class Instantiator
{

    /**
     * @var array
     */
    private static $reflections = [];

    /**
     * @param $klass
     * @return mixed
     */
    private static function getReflectionClass($klass)
    {
        if (!isset(static::$reflections[$klass])) {
            static::$reflections[$klass] = new \ReflectionClass($klass);
        }
        return static::$reflections[$klass];
    }

    /**
     * @param $klass
     * @param array $params
     * @return mixed|null
     * @throws ServiceConfigurationException
     * @throws ServiceNotFoundException
     * @throws \Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     */
    public static function instantiate($klass, $params = [])
    {
        /**
         * @var $reflectionClass \ReflectionClass
         */
        $reflectionClass = self::getReflectionClass($klass);
        $obj = null;
        if ($reflectionClass->isInstantiable()) {
            $constructor = $reflectionClass->getConstructor();
            if ($reflectionClass->implementsInterface(ComponentInterface::class)) {
                if (!empty($constructor)) {
                    $obj = self::loadMethodDependencies($reflectionClass, $constructor);
                } else {
                    $obj = new $klass;
                }
            }else{
                throw new MustImplementComponentException(
                    sprintf('%s class must implements  %s interface',
                        $reflectionClass->getName(),
                        ComponentInterface::class));
            }
        } else {
            throw new ServiceConfigurationException(sprintf('%s service constructor is private', $klass));
        }
        $obj = self::initializeParams($obj, $params);
        return $obj;
    }

    /**
     * @param $obj
     * @param $params
     * @return mixed
     * @throws ServiceConfigurationException
     * @throws \Exception
     */
    public static function initializeParams($obj, $params)
    {
        foreach ($params as $attr_name => $attr_value) {
            $method = "set" . ucfirst($attr_name);
            if (method_exists($obj, $method)) {
                if (!is_array($attr_value) && strpos($attr_value, "#") === 0) {
                    $service_id = trim($attr_value, "#");
                    $attr_value = ServiceLocator::get($service_id);
                }
                $obj->$method($attr_value);
            } else {
                throw new ServiceConfigurationException(
                    sprintf("property %s not found in object %s", $attr_name, json_encode($obj))
                );
            }
        }
        return $obj;
    }

    /**
     * @param $reflectionClass \ReflectionClass
     * @param $method \ReflectionMethod
     * @return mixed
     * @throws ServiceNotFoundException
     * @throws \Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     */
    private static function loadMethodDependencies($reflectionClass, $method)
    {
        $args = $method->getParameters();
        $re_args = [];
        if (count($args) > 0) {
            foreach ($args as $arg) {
                if ($arg->isDefaultValueAvailable()) {
                    $re_args[$arg->getName()] = $arg->getDefaultValue();
                    continue;
                }
                if (ServiceLocator::has($arg->getName())) {
                    $paramValue = ServiceLocator::get($arg->getName());
                } else if (!is_null($arg->getType()) && ServiceLocator::has($arg->getType()->getName())) {
                    $typeName = $arg->getType()->getName();
                    $paramValue = ServiceLocator::get($typeName);
                } else {
                    throw new ServiceNotFoundException(sprintf('service from "%s" not found in service container', $arg->getName()));
                }
                $re_args[$arg->getName()] = $paramValue;
            }
        }
        return $reflectionClass->newInstanceArgs($re_args);
    }

}