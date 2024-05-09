<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use Closure;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\UnknownScopeException;
use ReflectionException;
use ReflectionFunction;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

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
     * @throws ClassNotFoundException
     * @throws IdAlreadyExistsException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws UnknownScopeException
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