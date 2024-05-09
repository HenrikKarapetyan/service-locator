<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\DependencyInjector;
use henrik\sl\Exceptions\UnknownScopeException;
use ReflectionParameter;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

/**
 * Trait MethodORFunctionDependencyLoaderTrait.
 */
trait MethodORFunctionDependencyLoaderTrait
{
    /**
     * @param array<int, reflectionParameter> $methodParams
     *
     * @throws ServiceNotFoundException
     * @throws ClassNotFoundException
     * @throws IdAlreadyExistsException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws UnknownScopeException
     *
     * @return array<int, mixed>
     */
    private static function loadDependencies(array $methodParams): array
    {
        $injector = DependencyInjector::instance();
        $params   = [];
        if (!empty($methodParams)) {
            foreach ($methodParams as $param) {
                $params[] = $injector->get($param->getName());
            }
        }

        return $params;
    }
}