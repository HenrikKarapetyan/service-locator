<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 3:05 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Parsers;

use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\UndefinedModeException;
use henrik\sl\Definition;
use henrik\sl\DefinitionInterface;
use henrik\sl\Exceptions\InvalidConfigurationException;

/**
 * Class ArrayConfigParser.
 */
class ArrayConfigParser extends AbstractConfigParser
{
    /**
     * @param array<string, array<string, int|string>> $services
     *
     * @throws UndefinedModeException
     */
    public function __construct(
        private readonly array $services
    ) {
        parent::__construct();
    }

    /**
     * @throws IdAlreadyExistsException
     * @throws InvalidConfigurationException
     */
    public function parse(): void
    {
        foreach ($this->services as $scope => $serviceItems) {

            $this->parseEachScopeData($scope, $serviceItems);
        }

    }

    /**
     * @param array<int|string, string|array<array<int|string, mixed>>|string|null> $definitionArrayOrFile
     *
     * @throws InvalidConfigurationException
     *
     * @return DefinitionInterface
     */
    public function parseEachItem(array $definitionArrayOrFile): DefinitionInterface
    {
        $definition                = self::parseAsAssocArray($definitionArrayOrFile);
        $definition ?: $definition = self::parseWithoutId($definitionArrayOrFile);
        $definition ?: $definition = self::parseAsAliasOrParams($definitionArrayOrFile);

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
    private function parseAsAssocArray(array $definitionArray): ?DefinitionInterface
    {
        if (isset($definitionArray['id'], $definitionArray['class'])) {

            if (is_string($definitionArray['id']) && is_string($definitionArray['class'])) {

                $definition = new Definition();

                $definition->setId($definitionArray['id']);
                $definition->setClass($definitionArray['class']);
                if (isset($definitionArray['params'])) {

                    $definition->setParams($this->parseParams($definitionArray['params']));
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
    private function parseParams(null|array|string $params): array
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
    private function parseWithoutId(array $definitionArray): ?DefinitionInterface
    {
        if (isset($definitionArray[0])) {
            if (!is_string($definitionArray[0])) {
                throw new InvalidConfigurationException('The array first value must be string');
            }
            $definition = new Definition($definitionArray[0], $definitionArray[0]);
            $definition->setId($definitionArray[0]);
            $definition->setClass($definitionArray[0]);
            if (isset($definitionArray[1]) && is_array($definitionArray[1])) {
                $definition->setParams($this->parseParams($definitionArray[1]));
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
    private function parseAsAliasOrParams(array $definitionArray): DefinitionInterface
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
                $definition->setParams($this->parseParams($value));
            }
        }

        return $definition;
    }

    /**
     * @param string                    $scope
     * @param array<string, int|string> $serviceItems
     *
     * @throws InvalidConfigurationException
     * @throws IdAlreadyExistsException
     *
     * @return void
     */
    private function parseEachScopeData(string $scope, array $serviceItems): void
    {
        foreach ($serviceItems as $item) {
            /** @var array<int|string, array<array<int|string, mixed>>|string|null> $item */
            $definition = $this->parseEachItem($item);

            $this->set($scope, $definition);
        }
    }
}