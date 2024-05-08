<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Injector;
use ReflectionParameter;

/**
 * Trait MethodORFunctionDependencyLoaderTrait.
 */
trait MethodORFunctionDependencyLoaderTrait
{
    /**
     * @param array<int, reflectionParameter> $methodParams
     *
     * @throws ServiceNotFoundException
     *
     * @return array<int, mixed>
     */
    private static function loadDependencies(array $methodParams): array
    {
        $injector = Injector::instance();
        $params   = [];
        if (!empty($methodParams)) {
            foreach ($methodParams as $param) {
                $params[] = $injector->get($param->getName());
            }
        }

        return $params;
    }
}