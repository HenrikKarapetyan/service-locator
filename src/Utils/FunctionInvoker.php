<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use Closure;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\ClassNotFoundException;
use henrik\sl\Exceptions\UnknownScopeException;
use ReflectionException;
use ReflectionFunction;

/**
 * Class FunctionInvoker.
 */
class FunctionInvoker
{
    use MethodORFunctionDependencyLoaderTrait;

    /**
     * @param Closure                  $func
     * @param array<int|string, mixed> $args
     *
     * @throws ClassNotFoundException
     * @throws IdAlreadyExistsException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws UnknownScopeException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     *
     * @return mixed
     */
    public static function invoke(Closure $func, array $args = []): mixed
    {
        $refFunc = new ReflectionFunction($func);
        $params  = self::loadDependencies($refFunc->getParameters(), $args);

        return $refFunc->invokeArgs($params);
    }
}