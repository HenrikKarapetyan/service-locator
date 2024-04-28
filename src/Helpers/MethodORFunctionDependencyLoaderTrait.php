<?php

namespace henrik\sl\Helpers;

use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Injector;
use ReflectionParameter;

/**
 * Trait MethodORFunctionDependencyLoaderTrait.
 */
trait MethodORFunctionDependencyLoaderTrait
{
    /**
     * @param array $methodParams
     *
     * @throws ServiceNotFoundException
     *
     * @return array
     */
    private static function loadDependencies(array $methodParams): array
    {
        $injector = Injector::instance();
        $params   = [];
        if (!empty($method_params)) {
            foreach ($method_params as $param) {
                /**
                 * @var ReflectionParameter $param
                 */
                $params[] = $injector->get($param->getName());
            }
        }

        return $params;
    }
}