<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class IntegerValue implements ValueInterface
{
    public function __construct(
        private readonly ?int $minimum = null,
        private readonly ?int $maximum = null,
        private readonly ?bool $exclusiveMinimum = null,
        private readonly ?bool $exclusiveMaximum = null,
        private readonly ?int $multipleOf = null,
        private readonly ?int $example = null,
    ) {
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
}
