<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use Closure;
use henrik\container\exceptions\ServiceNotFoundException;
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
     * @throws ReflectionException
     * @throws ServiceNotFoundException
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