<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 3:05 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Utils;

use henrik\sl\Definition;

/**
 * Class ArrayConfigParser.
 */
class ArrayConfigParser
{
    /**
     * @param array<int|string, string|array<string, mixed>|null> $definitionArray
     *
     * @return Definition
     */
    public static function parse(array $definitionArray): Definition
    {
        $definition = new Definition();
        /**
         * [
         *      'id' =>'di',
         *      'class' => 'henrik\sl\DI',
         *      'params' => []
         * ].
         */
        if (
            isset($definitionArray['id'], $definitionArray['class'])
            && is_string($definitionArray['id'])
            && is_string($definitionArray['class'])
        ) {
            $definition->setId($definitionArray['id']);
            $definition->setClass($definitionArray['class']);
            if (isset($definitionArray['params'])) {
                /** @var array<string, array<string,mixed>> $definitionArray */
                $definition->setParams($definitionArray['params']);
            }

            return $definition;
            /**
             * [henrik\sl\Di,['dd'=>'dd']].
             */
        }
        if (isset($definitionArray[0]) && is_string($definitionArray[0])) {
            $definition = new Definition($definitionArray[0], $definitionArray[0]);
            $definition->setId($definitionArray[0]);
            $definition->setClass($definitionArray[0]);
            if (isset($definitionArray[1]) && is_array($definitionArray[1])) {
                $definition->setParams($definitionArray[1]);
            }

            return $definition;
        }
        /**
         * ['di'=>'henrik\sl\Di',['dd'=>'dd']].
         */
        foreach ($definitionArray as $key => $value) {
            if (is_string($key)) {
                $definition->setId($key);
                /** @var string $value */
                $definition->setValue($value);
            }
            if (is_array($value)) {
                /** @var array<string, mixed> $value */
                $definition->setParams($value);
            }
        }

        return $definition;
    }
}