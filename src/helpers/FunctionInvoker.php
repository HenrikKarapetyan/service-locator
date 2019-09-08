<?php


namespace henrik\sl\helpers;


/**
 * Class FunctionInvoker
 * @package henrik\sl\helpers
 */
class FunctionInvoker
{
    use MethodORFunctionDependencyLoaderTrait;

    /**
     * @param callable $func
     * @return mixed
     * @throws \ReflectionException
     */
    public static function invoke(callable $func)
    {
        $ref_func = new \ReflectionFunction($func);
        $params = static::loadDependencies($ref_func->getParameters());
        return $ref_func->invokeArgs($params);
    }

}