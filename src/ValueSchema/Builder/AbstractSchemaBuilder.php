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
     * @return static
     */
    final public function withSchemaName(?string $schemaName)
    {
        $this->schemaName = $schemaName;
        return $this;
    }

    /**
     * @return static
     */
    final public function withDeprecated(bool $deprecated)
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
