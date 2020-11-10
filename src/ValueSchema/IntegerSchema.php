<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

/**
 * @property-read ?int $minimum
 * @property-read ?int $maximum
 * @property-read ?bool $exclusiveMinimum
 * @property-read ?bool $exclusiveMaximum
 * @property-read ?int $multipleOf
 */
class IntegerSchema extends AbstractValueSchema
{
    protected ?int $minimum;
    protected ?int $maximum;
    protected ?bool $exclusiveMinimum;
    protected ?bool $exclusiveMaximum;
    protected ?int $multipleOf;

    public function __construct(
        ?int $minimum,
        ?int $maximum,
        ?bool $exclusiveMinimum,
        ?bool $exclusiveMaximum,
        ?int $multipleOf,
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

    protected function getMinimum(): ?int
    {
        return $this->minimum;
    }

    protected function getMaximum(): ?int
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

    protected function getMultipleOf(): ?int
    {
        return $this->multipleOf;
    }
}
