<?php


namespace henrik\sl;


use henrik\sl\exceptions\ServiceConfigurationException;
use henrik\sl\exceptions\ServiceNotFoundException;
use henrik\sl\exceptions\UnknownScopeException;
use henrik\sl\helpers\ArrayConfigParser;

/**
 * Class Injector
 * @package henrik\sl
 */
class Injector
{

    /**
     * @var ServicesContainer
     */
    private $serviceContainer;
    /**
     * @var ReflectionsContainer
     */
    private $reflectionsContainer;

    /**
     * Injector constructor.
     */
    public function __construct()
    {
        $this->serviceContainer = new ServicesContainer();
        $this->reflectionsContainer = new ReflectionsContainer();
    }

    /**
     * @param $services
     * @throws UnknownScopeException
     * @throws \henrik\container\exceptions\IdAlreadyExistsException
     * @throws \henrik\container\exceptions\TypeException
     */
    public function load($services)
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
                    $provider_inst = new $provider($this, $klass, $params);
                    $this->serviceContainer->set($parsed_item['id'], $provider_inst);
                }
            } else {
                throw new UnknownScopeException(sprintf('Unknown  scope "%s"', $scope));
            }
        }
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
    public function instantiate($klass, $params = [])
    {
        /**
         * @var $reflectionClass \ReflectionClass
         */
        $reflectionClass = $this->reflectionsContainer->getReflectionClass($klass);
        $obj = null;
        if ($reflectionClass->isInstantiable()) {
            $constructor = $reflectionClass->getConstructor();
//            if ($reflectionClass->implementsInterface(ComponentInterface::class)) {
            if (!empty($constructor)) {
                $obj = $this->loadMethodDependencies($reflectionClass, $constructor);
            } else {
                $obj = new $klass;
            }
//            } else {
//                throw new MustImplementComponentException(
//                    sprintf('%s class must implements  %s interface',
//                        $reflectionClass->getName(),
//                        ComponentInterface::class));
//            }
        } else {
            throw new ServiceConfigurationException(sprintf('%s service constructor is private', $klass));
        }
        $obj = $this->initializeParams($obj, $params);
        return $obj;
    }

    /**
     * @param $obj
     * @param $params
     * @return mixed
     * @throws ServiceConfigurationException
     * @throws \Exception
     */
    public function initializeParams($obj, $params)
    {
        foreach ($params as $attr_name => $attr_value) {
            $method = "set" . ucfirst($attr_name);
            if (method_exists($obj, $method)) {
                if (!is_array($attr_value) && strpos($attr_value, "#") === 0) {
                    $service_id = trim($attr_value, "#");
                    $attr_value = $this->serviceContainer->get($service_id);
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
    private function loadMethodDependencies($reflectionClass, $method)
    {
        $args = $method->getParameters();
        $re_args = [];
        if (count($args) > 0) {
            foreach ($args as $arg) {
                if ($arg->isDefaultValueAvailable()) {
                    $re_args[$arg->getName()] = $arg->getDefaultValue();
                    continue;
                }
                if ($this->serviceContainer->has($arg->getName())) {
                    $paramValue = $this->serviceContainer->get($arg->getName());
                } else if (!is_null($arg->getType()) && $this->serviceContainer->has($arg->getType()->getName())) {
                    $typeName = $arg->getType()->getName();
                    $paramValue = $this->serviceContainer->get($typeName);
                } else {
                    throw new ServiceNotFoundException(sprintf('service from "%s" not found in service container', $arg->getName()));
                }
                $re_args[$arg->getName()] = $paramValue;
            }
        }
        return $reflectionClass->newInstanceArgs($re_args);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     */
    public function get($id)
    {
        return $this->serviceContainer->get($id);
    }
}