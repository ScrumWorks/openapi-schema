<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

abstract class AbstractValueSchema implements ValueSchemaInterface
{
    protected bool $nullable;

    protected ?string $description;

    protected ?string $schemaName;

    public function __construct(bool $nullable = false, ?string $description = null, ?string $schemaName = null)
    {
        $this->nullable = $nullable;
        $this->description = $description;
        $this->schemaName = $schemaName;
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

    abstract protected function validate();
}
