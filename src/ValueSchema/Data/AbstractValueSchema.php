<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

abstract class AbstractValueSchema implements ValueSchemaInterface
{
    /**
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        protected bool $nullable = false,
        protected ?string $description = null,
        protected ?string $schemaName = null,
        protected bool $isDeprecated = false,
        protected array $metaData = [],
    ) {
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

    public function getMetaData(): array
    {
        return $this->metaData;
    }

    abstract protected function validate(): void;
}
