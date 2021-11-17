<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;

final class IntegerSchema extends AbstractValueSchema
{
    private ?int $minimum;

    private ?int $maximum;

    private ?bool $exclusiveMinimum;

    private ?bool $exclusiveMaximum;

    private ?int $multipleOf;

    public function __construct(
        ?int $minimum = null,
        ?int $maximum = null,
        ?bool $exclusiveMinimum = null,
        ?bool $exclusiveMaximum = null,
        ?int $multipleOf = null,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null
    ) {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->exclusiveMinimum = $exclusiveMinimum;
        $this->exclusiveMaximum = $exclusiveMaximum;
        $this->multipleOf = $multipleOf;

        parent::__construct($nullable, $description, $schemaName);
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

    protected function validate(): void
    {
        if ($this->minimum !== null && $this->minimum < 0) {
            throw new InvalidArgumentException(sprintf("Invalid value %d for argument 'minimum'", $this->minimum));
        }
        if ($this->maximum !== null && $this->maximum < 0) {
            throw new InvalidArgumentException(sprintf("Invalid value %d for argument 'maximum'", $this->maximum));
        }
        if ($this->minimum !== null && $this->maximum !== null && $this->maximum < $this->minimum) {
            throw new InvalidArgumentException(sprintf("Invalid value %d for argument 'maximum'", $this->maximum));
        }
        if ($this->minimum === null && $this->exclusiveMinimum !== null) {
            throw new InvalidArgumentException("Can't set 'exclusiveMinimum' without 'minimum' argument");
        }
        if ($this->maximum === null && $this->exclusiveMaximum !== null) {
            throw new InvalidArgumentException("Can't set 'exclusiveMaximum' without 'maximum' argument");
        }
    }
}
