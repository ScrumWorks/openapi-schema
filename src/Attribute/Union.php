<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Union implements ValueInterface
{
    /**
     * @param array<string, string>|null $mapping
     * @param ValueInterface[]|null $types
     */
    public function __construct(
        private readonly ?string $discriminator = null,
        private readonly ?array $mapping = null,
        private readonly ?array $types = null,
    ) {
    }

    public function getDiscriminator(): ?string
    {
        return $this->discriminator;
    }

    /**
     * @return array<string, string>|null
     */
    public function getMapping(): ?array
    {
        return $this->mapping;
    }

    /**
     * @return ValueInterface[]|null
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }
}
