<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

final class StringSchemaData extends AbstractValueSchema implements StringSchema
{
    /**
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly ?int $minLength = null,
        private readonly ?int $maxLength = null,
        private readonly ?string $format = null,
        private readonly ?string $pattern = null,
        private readonly ?string $example = null,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false,
        array $metaData = [],
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated, $metaData);
    }

    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function getExample(): ?string
    {
        return $this->example;
    }

    protected function validate(): void
    {
        if ($this->minLength !== null && $this->minLength < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'minLength'", $this->minLength));
        }
        if ($this->maxLength !== null && $this->maxLength < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maxLength'", $this->maxLength));
        }
        if ($this->minLength !== null && $this->maxLength !== null && $this->maxLength < $this->minLength) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maxLength'", $this->maxLength));
        }
        // TODO assert for $pattern
        // TODO $format and $pattern is probably exclusive
    }
}
