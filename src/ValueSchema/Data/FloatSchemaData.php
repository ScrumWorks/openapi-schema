<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

final class FloatSchemaData extends AbstractValueSchema implements FloatSchema
{
    public function __construct(
        private readonly ?float $minimum = null,
        private readonly ?float $maximum = null,
        private readonly ?bool $exclusiveMinimum = null,
        private readonly ?bool $exclusiveMaximum = null,
        private readonly ?float $multipleOf = null,
        private readonly ?float $example = null,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated);
    }

    public function getMinimum(): ?float
    {
        return $this->minimum;
    }

    public function getMaximum(): ?float
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

    public function getMultipleOf(): ?float
    {
        return $this->multipleOf;
    }

    public function getExample(): ?float
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
