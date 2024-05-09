<?php

declare(strict_types=1);

namespace henrik\sl;

class Definition implements DefinitionInterface
{
    /**
     * @var array<string, mixed> $params
     */
    private array $params = [];

    /**
     * @var array<string, mixed> $args
     */
    private array $args = [];

    private mixed $value = null;

    /**
     * @param string|null $id
     * @param string|null $class
     */
    public function __construct(
        private ?string $id = null,
        private ?string $class = null
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return array<string, mixed> $params
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return self
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /** {@inheritdoc} */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return $this
     */
    public function setArgs(array $args): self
    {
        $this->args = $args;

        return $this;
    }
}