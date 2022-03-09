<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

abstract class AbstractValueSchema implements ValueSchemaInterface
{
    protected bool $nullable;

    protected ?string $description;

    protected ?string $schemaName;

    protected bool $isDeprecated;

    public function __construct(
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false
    ) {
        $this->nullable = $nullable;
        $this->description = $description;
        $this->schemaName = $schemaName;
        $this->isDeprecated = $isDeprecated;
        $this->validate();
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
        return $this->isDeprecated;
    }

    abstract protected function validate();
}
