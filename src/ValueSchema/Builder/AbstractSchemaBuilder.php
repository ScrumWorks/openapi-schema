<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use Error;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

abstract class AbstractSchemaBuilder
{
    protected bool $nullable = false;

    protected ?string $description = null;

    /**
     * @var ?mixed
     */
    protected $example = null;

    /**
     * @return static
     */
    final public function withNullable(bool $nullable)
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @return static
     */
    final public function withDescription(?string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param ?mixed $example
     * @return static
     */
    final public function withExample($example)
    {
        $this->example = $example;
        return $this;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExample()
    {
        return $this->example;
    }

    public function build(): ValueSchemaInterface
    {
        $this->validate();
        return $this->createInstance();
    }

    protected function assertRequiredProperty(string $property): void
    {
        try {
            // @phpstan-ignore-next-line
            $this->{$property};
        } catch (Error $e) {
            throw new LogicException(\sprintf("Property '%s' isn't filled", $property));
        }
    }

    protected function validate(): void
    {
    }

    abstract protected function createInstance(): ValueSchemaInterface;
}
