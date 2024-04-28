<?php

declare(strict_types=1);

namespace henrik\sl\Helpers;

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
     * @param $obj
     * @param $method
     *
     * @throws MethodNotFoundException
     * @throws ReflectionException
     *
     * @return mixed|null
     */
    public static function invoke($obj, $method): mixed
    {
        if (is_object($obj)) {
            if (method_exists($obj, $method)) {
                $klass      = get_class($obj);
                $ref_method = new ReflectionMethod($klass, $method);
                $params     = static::loadDependencies($ref_method->getParameters());

                return $ref_method->invokeArgs($obj, $params);
            }

            throw new MethodNotFoundException(sprintf('method "%s" not found', $method));

        }

        return null;
    }
}