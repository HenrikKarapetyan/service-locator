<?php


namespace henrik\sl\helpers;


use henrik\sl\exceptions\MethodNotFoundException;
use ReflectionMethod;

/**
 * Class MethodInvoker
 * @package henrik\sl\helpers
 */
class MethodInvoker
{
    use MethodORFunctionDependencyLoaderTrait;

    /**
     * @param $obj
     * @param $method
     * @return mixed|null
     * @throws MethodNotFoundException
     * @throws \ReflectionException
     */
    public static function invoke($obj, $method)
    {
        if (is_object($obj)) {
            if (method_exists($obj, $method)) {
                $klass = get_class($obj);
                $ref_method = new ReflectionMethod($klass, $method);
                $params = static::loadDependencies($ref_method->getParameters());
                return $ref_method->invokeArgs($obj, $params);
            } else {
                throw new MethodNotFoundException(sprintf('method "%s" not found', $method));
            }
        }
        return null;
    }
}