<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

abstract class AbstractValueSchema implements ValueSchemaInterface
{
    protected bool $nullable;

    protected ?string $description;

    public function __construct(bool $nullable = false, ?string $description = null)
    {
        $this->nullable = $nullable;
        $this->description = $description;
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

    abstract protected function validate();
}
