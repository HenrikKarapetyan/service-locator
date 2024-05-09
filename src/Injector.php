<?php

declare(strict_types=1);

namespace henrik\sl;

use Exception;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\UndefinedModeException;
use henrik\sl\Exceptions\ServiceConfigurationException;
use henrik\sl\Exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\UnknownConfigurationException;
use henrik\sl\Exceptions\UnknownScopeException;
use henrik\sl\Providers\AliasProvider;
use henrik\sl\Providers\FactoryProvider;
use henrik\sl\Providers\ParamProvider;
use henrik\sl\Providers\PrototypeProvider;
use henrik\sl\Providers\SingletonProvider;
use henrik\sl\ServiceScopeInterfaces\FactoryAwareInterface;
use henrik\sl\ServiceScopeInterfaces\PrototypeAwareInterface;
use henrik\sl\ServiceScopeInterfaces\SingletonAwareInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

/**
 * Class Injector.
 */
class Injector
{
    use ConfigurationLoaderTrait;
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

    private InjectorModes $mode = InjectorModes::CONFIG_FILE;

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
     * @param array<string, array<string, int|string>>|string $services
     *
     * @throws UnknownScopeException
     * @throws IdAlreadyExistsException
     * @throws UndefinedModeException
     * @throws UnknownConfigurationException|Exceptions\InvalidConfigurationException
     */
    public function load(array|string $services): void
    {
        $data = $this->guessExtensionOrDataType($services);

        foreach ($data as $scope => $definitionArray) {
            foreach ($definitionArray as $definition) {
                $this->add($scope, $definition);
            }
        }
    }

    /**
     * @param string               $klass
     * @param array<string, mixed> $params
     *
     * @throws \henrik\container\exceptions\ServiceNotFoundException
     * @throws IdAlreadyExistsException
     * @throws ServiceConfigurationException
     * @throws ServiceNotFoundException
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
     * @throws ServiceNotFoundException|\henrik\container\exceptions\ServiceNotFoundException
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
     * @throws ClassNotFoundException*@throws IdAlreadyExistsException
     * @throws ServiceNotFoundException|IdAlreadyExistsException
     * @throws \henrik\container\exceptions\ServiceNotFoundException|UnknownScopeException
     *
     * @return mixed
     */
    public function get(string $id): mixed
    {
        $dataFromContainer = $this->serviceContainer->get($id);
        if ($this->mode == InjectorModes::AUTO_REGISTER) {
            if ($dataFromContainer === null) {

                if (!class_exists($id)) {
                    throw new ClassNotFoundException($id);
                }

                $scope      = $this->guessServiceScope($id);
                $definition = new Definition($id, $id);
                $this->add($scope->value, $definition);

                return $this->serviceContainer->get($id);
            }

        }

        if (is_null($dataFromContainer)) {
            throw new ServiceNotFoundException($id);
        }

        return $dataFromContainer;

    }

    public function getMode(): InjectorModes
    {
        return $this->mode;
    }

    public function setMode(InjectorModes $mode): void
    {
        $this->mode = $mode;
    }

    public function dumpContainer(): void
    {
        foreach ($this->serviceContainer->getAll() as $id => $containerItem) {
            if (is_object($containerItem)) {
                printf("id: %s, class: %s \n", $id, get_class($containerItem));
            }
        }
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param ReflectionMethod        $method
     *
     * @throws ServiceNotFoundException
     * @throws Exception
     * @throws \henrik\container\exceptions\ServiceNotFoundException
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
                $paramValue = $this->getValueFromContainer($arg);

                $reArgs[$arg->getName()] = $paramValue;
            }
        }

        return $reflectionClass->newInstanceArgs($reArgs);
    }

    /**
     * @param string              $scope
     * @param DefinitionInterface $definition
     *
     * @throws UnknownScopeException
     * @throws IdAlreadyExistsException
     */
    private function add(string $scope, DefinitionInterface $definition): void
    {

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

    /**
     * @param ReflectionParameter $arg
     *
     * @throws UnknownScopeException
     * @throws \henrik\container\exceptions\ServiceNotFoundException|IdAlreadyExistsException
     * @throws ClassNotFoundException
     * @throws ServiceNotFoundException
     *
     * @return mixed
     */
    private function getValueFromContainer(ReflectionParameter $arg): mixed
    {
        if ($this->serviceContainer->has($arg->getName())) {
            return $this->serviceContainer->get($arg->getName());
        }

        if (!$arg->getType() instanceof ReflectionNamedType) {
            throw new ClassNotFoundException($arg->getName());
        }

        if ($this->mode !== InjectorModes::AUTO_REGISTER) {

            if ($this->serviceContainer->has($arg->getType()->getName())) {
                $typeName = $arg->getType()->getName();

                return $this->serviceContainer->get($typeName);
            }

            throw new ServiceNotFoundException(sprintf('Service from "%s" not found in service container', $arg->getType()->getName()));

        }
        $typeName = $arg->getType()->getName();

        return $this->get($typeName);
    }

    private function guessServiceScope(string $id): ServiceScope
    {
        $classImplementedInterfaces = class_implements($id);

        if (is_array($classImplementedInterfaces) && count($classImplementedInterfaces) > 0) {

            if (in_array(SingletonAwareInterface::class, $classImplementedInterfaces)) {
                return ServiceScope::SINGLETON;
            }

            if (in_array(PrototypeAwareInterface::class, $classImplementedInterfaces)) {
                return ServiceScope::PROTOTYPE;
            }

            if (in_array(FactoryAwareInterface::class, $classImplementedInterfaces)) {
                return ServiceScope::FACTORY;
            }
        }

        return ServiceScope::SINGLETON;
    }
}