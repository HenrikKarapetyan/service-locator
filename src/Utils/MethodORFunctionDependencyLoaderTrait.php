<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\DependencyInjector;
use henrik\sl\Exceptions\ClassNotFoundException;
use henrik\sl\Exceptions\UnknownScopeException;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Trait MethodORFunctionDependencyLoaderTrait.
 */
trait MethodORFunctionDependencyLoaderTrait
{
    /**
     * @param array<int, reflectionParameter> $methodParams
     *
     * @throws IdAlreadyExistsException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws UnknownScopeException|ClassNotFoundException
     * @throws ServiceNotFoundException
     *
     * @return array<int, mixed>
     */
    private static function loadDependencies(array $methodParams): array
    {
        $injector = DependencyInjector::instance();
        $params   = [];

        if (!empty($methodParams)) {

            foreach ($methodParams as $param) {

                if (!$param->getType() instanceof ReflectionNamedType) {
                    throw new ClassNotFoundException($param->getName());
                }

                if ($injector->has($param->getName())) {
                    $params[] = $injector->get($param->getName());
                }

                $params[] = $injector->get($param->getType()->getName());
            }
        }

        return $params;
    }
}