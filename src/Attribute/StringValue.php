<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class StringValue implements ValueInterface
{
    public function __construct(
        private readonly ?int $minLength = null,
        private readonly ?int $maxLength = null,
        private readonly ?string $format = null,
        private readonly ?string $pattern = null,
    ) {
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
}
