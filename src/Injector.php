<?php

declare(strict_types=1);

namespace henrik\sl;

use Exception;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\sl\Exceptions\ServiceConfigurationException;
use henrik\sl\Exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\UnknownScopeException;
use henrik\sl\Providers\AliasProvider;
use henrik\sl\Providers\FactoryProvider;
use henrik\sl\Providers\ParamProvider;
use henrik\sl\Providers\PrototypeProvider;
use henrik\sl\Providers\SingletonProvider;
use henrik\sl\Utils\ArrayConfigParser;
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
     * @var RCContainer
     */
    private RCContainer $reflectionsContainer;

    /**
     * Injector constructor.
     */
    private function __construct()
    {
        $this->serviceContainer     = new ServicesContainer();
        $this->reflectionsContainer = new RCContainer();
    }

    /**
     * @return self
     */
    public static function instance(): Injector
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array<string, array<string, int|string>> $services
     *
     * @throws UnknownScopeException
     * @throws IdAlreadyExistsException
     */
    public function load(array $services): void
    {
        foreach ($services as $scope => $serviceItems) {

            foreach ($serviceItems as $item) {
                /** @var array<string, string|array<string, mixed>> $item */
                $definition = ArrayConfigParser::parse($item);

                $providerInst = match ($scope) {
                    ServiceScope::SINGLETON->value => new SingletonProvider($this, $definition),
                    ServiceScope::FACTORY->value   => new FactoryProvider($this, $definition),
                    ServiceScope::PROTOTYPE->value => new PrototypeProvider($this, $definition),
                    ServiceScope::ALIAS->value     => new AliasProvider($this, $definition),
                    ServiceScope::PARAM->value     => new ParamProvider($this, $definition),
                    default                        => throw new UnknownScopeException(sprintf('Unknown  scope "%s"', $scope)),

                };
                $this->serviceContainer->set((string) $definition->getId(), $providerInst);
            }
        }
    }

    /**
     * @param string               $klass
     * @param array<string, mixed> $params
     *
     * @throws ServiceNotFoundException
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     * @throws IdAlreadyExistsException
     * @throws ServiceConfigurationException
     *
     * @return object
     */
    public function instantiate(string $klass, array $params = []): object
    {
        /**
         * @var ReflectionClass<object> $reflectionClass
         */
        $reflectionClass = $this->reflectionsContainer->getReflectionClass($klass);
        if (!$reflectionClass->isInstantiable()) {
            throw new ServiceConfigurationException(sprintf('%s service constructor is private', $klass));
        }

        $constructor = $reflectionClass->getConstructor();
        if (empty($constructor)) {

            $obj = new $klass();

            return $this->initializeParams($obj, $params);
        }

        $obj = $this->loadMethodDependencies($reflectionClass, $constructor);

        return $this->initializeParams($obj, $params);
    }

    /**
     * @param object               $obj
     * @param array<string, mixed> $params
     *
     * @throws ServiceConfigurationException
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     *
     * @return object
     */
    public function initializeParams(object $obj, array $params): object
    {
        /** @var string|array<string, array<string, string>|string> $attrValue */
        foreach ($params as $attrName => $attrValue) {
            $method = 'set' . ucfirst($attrName);

            if (!method_exists($obj, $method)) {
                throw new ServiceConfigurationException(
                    sprintf('Property %s not found in object %s', $attrName, json_encode($obj))
                );
            }

            if (!is_array($attrValue) && str_starts_with($attrValue, '#')) {
                $serviceId = trim($attrValue, '#');
                $attrValue = $this->serviceContainer->get($serviceId);
            }
            $obj->{$method}($attrValue);
        }

        return $obj;
    }

    /**
     * @param string $id
     *
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     *
     * @return mixed
     */
    public function get(string $id): mixed
    {
        return $this->serviceContainer->get($id);
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param ReflectionMethod        $method
     *
     * @throws Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     * @throws ServiceNotFoundException
     *
     * @return object
     */
    private function loadMethodDependencies(ReflectionClass $reflectionClass, ReflectionMethod $method): object
    {
        $args   = $method->getParameters();
        $reArgs = [];
        if (count($args) > 0) {
            foreach ($args as $arg) {
                if ($arg->isDefaultValueAvailable()) {
                    $reArgs[$arg->getName()] = $arg->getDefaultValue();

                    continue;
                }
                if ($this->serviceContainer->has($arg->getName())) {
                    $paramValue = $this->serviceContainer->get($arg->getName());
                } elseif (!is_null($arg->getType()) && $this->serviceContainer->has($arg->getType()->getName())) {
                    $typeName   = $arg->getType()->getName();
                    $paramValue = $this->serviceContainer->get($typeName);
                } else {
                    throw new ServiceNotFoundException(sprintf('Service from "%s" not found in service container', $arg->getName()));
                }
                $reArgs[$arg->getName()] = $paramValue;
            }
        }

        return $reflectionClass->newInstanceArgs($reArgs);
    }
}