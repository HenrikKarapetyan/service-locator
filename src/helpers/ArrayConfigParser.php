<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 3:05 PM
 */

namespace henrik\sl\helpers;

/**
 * Class ArrayConfigParser
 * @package henrik\sl\helpers
 */
class ArrayConfigParser
{
    public static function parse($definition)
    {
        $item = [];
        /**
         * [
         *      'id' =>'di',
         *      'class' => 'henrik\sl\DI',
         *      'params' => []
         * ]
         */
        if (isset($definition['id']) && isset($definition['class'])) {
            $item['id'] = $definition['id'];
            $class = $definition['class'];
            $item['class'] = $class;
            if (isset($definition['params'])) {
                $item['params'] = $definition['params'];
            }
            /**
             * [henrik\sl\Di,['dd'=>'dd']]
             */
        } else if (isset($definition[0]) && is_string($definition[0])) {
            $item['id'] = $definition[0];
            $item['class'] = $definition[0];
            if (isset($definition[1]) && is_array($definition[1])) {
                $item['params'] = $definition[1];
            }

        } else {
            /**
             * ['di'=>'henrik\sl\Di',['dd'=>'dd']]
             */
            foreach ($definition as $key => $value) {
                if (is_string($key)) {
                    $item['id'] = $key;
                    $item['class'] = $definition[$key];
                } else if (is_array($definition[$key])) {
                    $item['params'] = $value;
                }
            }
        }
        return $item;
    }

}