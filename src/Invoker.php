<?php


namespace henrik\sl;


/**
 * Class Invoker
 * @package sparrow\core
 */
class Invoker
{

    /**
     * @param callable $func
     * @return mixed
     * @throws \Exception
     * @throws \sparrow\container\exceptions\ServiceNotFoundException
     */
    public static function invoke_function(callable $func)
    {
        $ref_func = new \ReflectionFunction($func);
        $params = self::loadDependencies($ref_func->getParameters());
        return $ref_func->invokeArgs($params);
    }

    /**
     * @param $obj
     * @param $method
     * @return mixed|null
     * @throws MethodNotFoundException
     * @throws \Exception
     * @throws \sparrow\container\exceptions\ServiceNotFoundException
     */
    public static function invoke_method($obj, $method)
    {
        if (is_object($obj)) {
            if (method_exists($obj, $method)) {
                $klass = get_class($obj);
                $ref_method = new \ReflectionMethod($klass, $method);
                $params = self::loadDependencies($ref_method->getParameters());
                return $ref_method->invokeArgs($obj, $params);
            } else {
                throw new MethodNotFoundException(sprintf('method "%s" not found', $method));
            }
        }
        return null;
    }


    /**
     * @param $method_params
     * @return array
     * @throws \Exception
     * @throws \sparrow\container\exceptions\ServiceNotFoundException
     */
    private static function loadDependencies($method_params)
    {
        $params = [];
        if (!empty($method_params)) {
            foreach ($method_params as $param) {
                /**
                 * @var $param \ReflectionParameter
                 */
                $params[] = ServiceLocator::get($param->getName());
            }
        }
        return $params;
    }

}