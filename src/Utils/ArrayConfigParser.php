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
use henrik\sl\DefinitionInterface;
use henrik\sl\Exceptions\InvalidConfigurationException;

/**
 * Class ArrayConfigParser.
 */
class ArrayConfigParser
{
    /**
     * @param array<int|string, string|array<array<int|string, mixed>>|string|null> $definitionArray
     *
     * @throws InvalidConfigurationException
     *
     * @return DefinitionInterface
     */
    public static function parse(array $definitionArray): DefinitionInterface
    {
        $definition                = self::parseAsAssocArray($definitionArray);
        $definition ?: $definition = self::parseWithoutId($definitionArray);
        $definition ?: $definition = self::parseAsAliasOrParams($definitionArray);

        return $definition;
    }

    /**
     * [
     *      'id' =>'di',
     *      'class' => 'henrik\sl\DI',
     *      'params' => []
     * ].
     *
     * @param array<int|string, string|array<array<int|string, mixed>>|string|null> $definitionArray
     *
     * @throws InvalidConfigurationException
     *
     * @return ?DefinitionInterface
     */
    private static function parseAsAssocArray(array $definitionArray): ?DefinitionInterface
    {
        if (isset($definitionArray['id'], $definitionArray['class'])) {

            if (is_string($definitionArray['id']) && is_string($definitionArray['class'])) {

                $definition = new Definition();

                $definition->setId($definitionArray['id']);
                $definition->setClass($definitionArray['class']);
                if (isset($definitionArray['params'])) {

                    $definition->setParams(self::parseParams($definitionArray['params']));
                }

                return $definition;
            }

            throw new InvalidConfigurationException(
                'Invalid configuration! The keys `id` and `class` are required and must be strings.'
            );
        }

        return null;
    }

    /**
     * @param array<array<int|string, mixed>>|string|null $params
     *
     * @throws InvalidConfigurationException
     *
     * @return array<string, mixed>
     */
    private static function parseParams(null|array|string $params): array
    {

        $parsedParams = [];
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (!is_string($key)) {
                    throw new InvalidConfigurationException('The `params` option must be assoc array and `key` must be string');
                }
                $parsedParams[$key] = $value;
            }
        }

        return $parsedParams;
    }

    /**
     * [
     *      henrik\sl\Di,['dd'=>'dd']
     * ].
     *
     * @param array<int|string, string|array<array<int|string, mixed>>|string|null> $definitionArray
     *
     * @throws InvalidConfigurationException
     *
     * @return ?DefinitionInterface
     */
    private static function parseWithoutId(array $definitionArray): ?DefinitionInterface
    {
        if (isset($definitionArray[0])) {
            if (!is_string($definitionArray[0])) {
                throw new InvalidConfigurationException('The array first value must be string');
            }
            $definition = new Definition($definitionArray[0], $definitionArray[0]);
            $definition->setId($definitionArray[0]);
            $definition->setClass($definitionArray[0]);
            if (isset($definitionArray[1]) && is_array($definitionArray[1])) {
                $definition->setParams(self::parseParams($definitionArray[1]));
            }

            return $definition;
        }

        return null;
    }

    /**
     * @param array<int|string, string|array<array<int|string, mixed>>|string|null> $definitionArray
     *
     * @throws InvalidConfigurationException
     *
     * @return DefinitionInterface
     */
    private static function parseAsAliasOrParams(array $definitionArray): DefinitionInterface
    {
        $definition = new Definition();
        foreach ($definitionArray as $key => $value) {
            if (is_string($key)) {
                $definition->setId($key);
                /** @var string $value */
                $definition->setValue($value);
            }
            if (is_array($value)) {
                /** @var array<array<int|string, mixed>>|string|null $value */
                $definition->setParams(self::parseParams($value));
            }
        }

        return $definition;
    }
}