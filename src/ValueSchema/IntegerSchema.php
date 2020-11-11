<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

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
        ?string $description = null
    ) {
        parent::__construct($nullable, $description);

        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->exclusiveMinimum = $exclusiveMinimum;
        $this->exclusiveMaximum = $exclusiveMaximum;
        $this->multipleOf = $multipleOf;
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
}
