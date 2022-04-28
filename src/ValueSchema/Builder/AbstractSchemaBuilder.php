<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

abstract class AbstractSchemaBuilder
{
    protected bool $nullable = false;

    protected ?string $description = null;

    protected ?string $schemaName = null;

    protected bool $deprecated = false;

    final public function withNullable(bool $nullable): static
    {
        $this->nullable = $nullable;
        return $this;
    }

    final public function withDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    final public function withSchemaName(?string $schemaName): static
    {
        $this->schemaName = $schemaName;
        return $this;
    }

    final public function withDeprecated(bool $deprecated): static
    {
        $this->deprecated = $deprecated;
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

    public function getSchemaName(): ?string
    {
        return $this->schemaName;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    abstract public function build(): ValueSchemaInterface;
}
