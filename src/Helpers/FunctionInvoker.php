<?php

declare(strict_types=1);

namespace henrik\sl\Helpers;

use ReflectionException;
use ReflectionFunction;

/**
 * Class FunctionInvoker.
 */
class FunctionInvoker
{
    use MethodORFunctionDependencyLoaderTrait;

    /**
     * @param callable $func
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function invoke(callable $func): mixed
    {
        $ref_func = new ReflectionFunction($func);
        $params   = static::loadDependencies($ref_func->getParameters());

        return $ref_func->invokeArgs($params);
    }
}