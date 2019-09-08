<?php


namespace henrik\sl\helpers;


use henrik\sl\Injector;

/**
 * Trait MethodORFunctionDependencyLoaderTrait
 * @package henrik\sl\helpers
 */
trait MethodORFunctionDependencyLoaderTrait
{
    /**
     * @param $method_params
     * @return array
     */
    private static function loadDependencies($method_params)
    {
        $injector = Injector::instance();
        $params = [];
        if (!empty($method_params)) {
            foreach ($method_params as $param) {
                /**
                 * @var $param \ReflectionParameter
                 */
                $params[] =$injector->get($param->getName());
            }
        }
        return $params;
    }
}