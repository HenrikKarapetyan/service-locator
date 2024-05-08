<?php

declare(strict_types=1);

namespace henrik\sl\Utils;

use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\MethodNotFoundException;
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
     * @throws MethodNotFoundException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
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