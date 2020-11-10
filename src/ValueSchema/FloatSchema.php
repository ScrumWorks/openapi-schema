<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

/**
 * @property-read ?float $minimum
 * @property-read ?float $maximum
 * @property-read ?bool $exclusiveMinimum
 * @property-read ?bool $exclusiveMaximum
 * @property-read ?float $multipleOf
 */
class FloatSchema extends AbstractValueSchema
{
    protected ?float $minimum;
    protected ?float $maximum;
    protected ?bool $exclusiveMinimum;
    protected ?bool $exclusiveMaximum;
    protected ?float $multipleOf;

    public function __construct(
        ?float $minimum,
        ?float $maximum,
        ?bool $exclusiveMinimum,
        ?bool $exclusiveMaximum,
        ?float $multipleOf,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->exclusiveMinimum = $exclusiveMinimum;
        $this->exclusiveMaximum = $exclusiveMaximum;
        $this->multipleOf = $multipleOf;
    }

    protected function getMinimum(): ?float
    {
        return $this->minimum;
    }

    protected function getMaximum(): ?float
    {
        return $this->maximum;
    }

    protected function getExclusiveMinimum(): ?bool
    {
        return $this->exclusiveMinimum;
    }

    protected function getExclusiveMaximum(): ?bool
    {
        return $this->exclusiveMaximum;
    }

    protected function getMultipleOf(): ?float
    {
        return $this->multipleOf;
    }
}
