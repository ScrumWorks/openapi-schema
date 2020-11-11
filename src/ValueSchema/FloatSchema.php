<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class FloatSchema extends AbstractValueSchema
{
    private ?float $minimum;

    private ?float $maximum;

    private ?bool $exclusiveMinimum;

    private ?bool $exclusiveMaximum;

    private ?float $multipleOf;

    public function __construct(
        ?float $minimum = null,
        ?float $maximum = null,
        ?bool $exclusiveMinimum = null,
        ?bool $exclusiveMaximum = null,
        ?float $multipleOf = null,
        bool $nullable = false,
        ?string $description = null
    ) {
        parent::__construct($nullable, $description);

        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->exclusiveMinimum = $exclusiveMinimum;
        $this->exclusiveMaximum = $exclusiveMaximum;
        $this->multipleOf = $multipleOf;
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
}
