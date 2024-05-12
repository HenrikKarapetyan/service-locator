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
     * @param Closure $func
     *
     * @throws ServiceNotFoundException
     * @throws IdAlreadyExistsException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws UnknownScopeException|ClassNotFoundException
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function invoke(Closure $func): mixed
    {
        $refFunc = new ReflectionFunction($func);
        $params  = self::loadDependencies($refFunc->getParameters());

        return $refFunc->invokeArgs($params);
    }
}