<?php

namespace henrik\sl\Parsers;

use henrik\sl\DefinitionInterface;

interface ConfigParserInterface
{
    /**
     * @return void
     */
    public function parse(): void;

    /**
     * @return array<string, array<DefinitionInterface>>
     */
    public function getAll(): array;
}