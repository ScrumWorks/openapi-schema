<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class HashmapValue implements ValueInterface
{
    /**
     * @param string[] $requiredProperties
     */
    public function __construct(
        private readonly array $requiredProperties = [],
        private readonly ?ValueInterface $itemsSchema = null,
    ) {
    }

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }

    public function getItemsSchema(): ?ValueInterface
    {
        return $this->itemsSchema;
    }
}
