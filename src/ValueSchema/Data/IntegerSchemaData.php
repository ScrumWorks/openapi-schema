<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

final class IntegerSchemaData extends AbstractValueSchema implements IntegerSchema
{
    /**
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly ?int $minimum = null,
        private readonly ?int $maximum = null,
        private readonly ?bool $exclusiveMinimum = null,
        private readonly ?bool $exclusiveMaximum = null,
        private readonly ?int $multipleOf = null,
        private readonly ?int $example = null,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false,
        array $metaData = [],
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated, $metaData);
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function getExclusiveMinimum(): ?bool
    {
        return $this->exclusiveMinimum;
    }

    public function getExclusiveMaximum(): ?bool
    {
        return $this->exclusiveMaximum;
    }

    public function getMultipleOf(): ?int
    {
        return $this->multipleOf;
    }

    public function getExample(): ?int
    {
        return $this->example;
    }

    protected function validate(): void
    {
        if ($this->minimum !== null && $this->minimum < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'minimum'", $this->minimum));
        }
        if ($this->maximum !== null && $this->maximum < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maximum'", $this->maximum));
        }
        if ($this->minimum !== null && $this->maximum !== null && $this->maximum < $this->minimum) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maximum'", $this->maximum));
        }
        if ($this->minimum === null && $this->exclusiveMinimum !== null) {
            throw new InvalidArgumentException("Can't set 'exclusiveMinimum' without 'minimum' argument");
        }
        if ($this->maximum === null && $this->exclusiveMaximum !== null) {
            throw new InvalidArgumentException("Can't set 'exclusiveMaximum' without 'maximum' argument");
        }
    }
}
