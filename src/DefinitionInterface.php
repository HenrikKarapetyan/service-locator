<?php

declare(strict_types=1);

namespace henrik\sl;

interface DefinitionInterface
{
    public function getId(): ?string;

    public function getClass(): ?string;

    /**
     * The system trying to set params by setters.
     *
     * @return array<string, mixed> $params
     */
    public function getParams(): array;

    public function getValue(): mixed;

    /** @return array<string, mixed>*/
    public function getArgs(): array;
}