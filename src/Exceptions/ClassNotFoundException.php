<?php

namespace henrik\sl\Exceptions;

use Throwable;

class ClassNotFoundException extends InjectorException
{
    public function __construct(string $idOrClass, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Class or id "%s" not found.', $idOrClass), $code, $previous);
    }
}