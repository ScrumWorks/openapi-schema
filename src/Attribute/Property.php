<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Property
{
    public function __construct(
        private readonly ?string $description = null,
        private readonly ?bool $required = null,
        private readonly ?bool $nullable = null,
        private readonly ?bool $deprecated = null,
    ) {
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function getNullable(): ?bool
    {
        return $this->nullable;
    }

    public function getDeprecated(): ?bool
    {
        return $this->deprecated;
    }
}
