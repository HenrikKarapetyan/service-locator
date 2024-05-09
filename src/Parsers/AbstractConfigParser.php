<?php

namespace henrik\sl\Parsers;

use henrik\container\Container;
use henrik\container\ContainerModes;
use henrik\container\exceptions\UndefinedModeException;

abstract class AbstractConfigParser extends Container implements ConfigParserInterface
{
    /**
     * @throws UndefinedModeException
     */
    public function __construct()
    {
        $this->changeMode(ContainerModes::MULTIPLE_VALUE_MODE);
    }
}