<?php

declare(strict_types=1);

namespace henrik\sl;

use Exception;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\sl\Exceptions\ServiceConfigurationException;
use henrik\sl\Exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\UnknownScopeException;
use henrik\sl\Helpers\ArrayConfigParser;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Injector.
 */
class Injector
{
    /**
     * @var ?self
     */
    private static ?Injector $instance = null;
    /**
     * @var ServicesContainer
     */
    private ServicesContainer $serviceContainer;
    /**
     * @var ReflectionsContainer
     */
    private ReflectionsContainer $reflectionsContainer;

    /**
     * Injector constructor.
     */
    private function __construct()
    {
        $this->serviceContainer     = new ServicesContainer();
        $this->reflectionsContainer = new ReflectionsContainer();
    }

    /**
     * @return self
     */
    public static function instance(): Injector
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param $services
     *
     * @throws UnknownScopeException
     * @throws IdAlreadyExistsException
     */
    public function load($services)
    {
        foreach ($services as $scope => $service_items) {

            if (!$this->isValidScope($scope)) {
                throw new UnknownScopeException(sprintf('Unknown  scope "%s"', $scope));
            }

            foreach ($service_items as $item) {
                $parsedItem = ArrayConfigParser::parse($item);
                $provider   = '\\henrik\\sl\\Providers\\' . ucfirst($scope . 'Provider');
                $klass      = $parsedItem['class'];
                $params     = [];

                if (isset($parsedItem['params'])) {
                    $params = $parsedItem['params'];
                }

                $provider_inst = new $provider($this, $klass, $params);
                $this->serviceContainer->set($parsedItem['id'], $provider_inst);
            }
        }
    }

    /**
     * @param string               $klass
     * @param array<string, mixed> $params
     *
     * @throws IdAlreadyExistsException
     * @throws ServiceConfigurationException
     * @throws ServiceNotFoundException
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     *
     * @return mixed|null
     */
    public function instantiate(string $klass, array $params = []): mixed
    {
        /**
         * @var ReflectionClass $reflectionClass
         */
        $reflectionClass = $this->reflectionsContainer->getReflectionClass($klass);
        $obj             = null;
        if ($reflectionClass->isInstantiable()) {
            $constructor = $reflectionClass->getConstructor();
            //            if ($reflectionClass->implementsInterface(ComponentInterface::class)) {
            if (!empty($constructor)) {
                $obj = $this->loadMethodDependencies($reflectionClass, $constructor);
            } else {
                $obj = new $klass();
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

        return $this->initializeParams($obj, $params);
    }

    /**
     * @param object               $obj
     * @param array<string, mixed> $params
     *
     * @throws ServiceConfigurationException
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     *
     * @return mixed
     */
    public function initializeParams(object $obj, array $params): mixed
    {
        foreach ($params as $attr_name => $attr_value) {
            $method = 'set' . ucfirst($attr_name);
            if (method_exists($obj, $method)) {
                if (!is_array($attr_value) && str_starts_with($attr_value, '#')) {
                    $service_id = trim($attr_value, '#');
                    $attr_value = $this->serviceContainer->get($service_id);
                }
                $obj->{$method}($attr_value);
            } else {
                throw new ServiceConfigurationException(
                    sprintf('property %s not found in object %s', $attr_name, json_encode($obj))
                );
            }
        }

        return $obj;
    }

    /**
     * @param $id
     *
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     *
     * @return mixed
     */
    public function get($id): mixed
    {
        return $this->serviceContainer->get($id);
    }

    /**
     * @param $reflectionClass \ReflectionClass
     * @param $method          \ReflectionMethod
     *
     * @throws ServiceNotFoundException
     * @throws Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     *
     * @return mixed
     */
    private function loadMethodDependencies(ReflectionClass $reflectionClass, ReflectionMethod $method): mixed
    {
        $args    = $method->getParameters();
        $re_args = [];
        if (count($args) > 0) {
            foreach ($args as $arg) {
                if ($arg->isDefaultValueAvailable()) {
                    $re_args[$arg->getName()] = $arg->getDefaultValue();

                    continue;
                }
                if ($this->serviceContainer->has($arg->getName())) {
                    $paramValue = $this->serviceContainer->get($arg->getName());
                } elseif (!is_null($arg->getType()) && $this->serviceContainer->has($arg->getType()->getName())) {
                    $typeName   = $arg->getType()->getName();
                    $paramValue = $this->serviceContainer->get($typeName);
                } else {
                    throw new ServiceNotFoundException(sprintf('service from "%s" not found in service container', $arg->getName()));
                }
                $re_args[$arg->getName()] = $paramValue;
            }
        }

        return $reflectionClass->newInstanceArgs($re_args);
    }

    private function isValidScope(int|string $scope): bool
    {
        return (bool) ServiceScope::tryFrom($scope);
    }
}