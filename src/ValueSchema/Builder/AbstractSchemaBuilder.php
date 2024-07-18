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

    /**
     * @var array<string, mixed>
     */
    protected array $metaData = [];

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

    /**
     * @param array<string, mixed> $metaData
     */
    final public function withMetaData(array $metaData): static
    {
        $this->metaData = $metaData;
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

    /**
     * @return array<string, mixed>
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    abstract public function build(): ValueSchemaInterface;
}
