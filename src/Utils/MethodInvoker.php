<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\ClassNotFoundException;
use henrik\sl\Exceptions\MethodNotFoundException;
use henrik\sl\Exceptions\UnknownScopeException;
use ReflectionException;
use ReflectionMethod;

/**
 * Class MethodInvoker.
 */
class MethodInvoker
{
    use MethodORFunctionDependencyLoaderTrait;

    /**
     * @param object $obj
     * @param string $method
     *
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws IdAlreadyExistsException
     * @throws \henrik\sl\Exceptions\ServiceNotFoundException
     * @throws UnknownScopeException|ClassNotFoundException
     * @throws MethodNotFoundException
     *
     * @return mixed|null
     */
    public static function invoke(object $obj, string $method): mixed
    {
        if (method_exists($obj, $method)) {
            $klass     = get_class($obj);
            $refMethod = new ReflectionMethod($klass, $method);
            $params    = self::loadDependencies($refMethod->getParameters());

            return $refMethod->invokeArgs($obj, $params);
        }

        throw new MethodNotFoundException(sprintf('method "%s" not found', $method));
    }
}