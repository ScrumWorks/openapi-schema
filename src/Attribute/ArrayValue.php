<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class ArrayValue implements ValueInterface
{
    public function __construct(
        private readonly ?int $minItems = null,
        private readonly ?int $maxItems = null,
        private readonly ?bool $uniqueItems = null,
        private readonly ?ValueInterface $itemsSchema = null,
    ) {
    }

    public function getMinItems(): ?int
    {
        return $this->minItems;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    public function getUniqueItems(): ?bool
    {
        return $this->uniqueItems;
    }

    public function getItemsSchema(): ?ValueInterface
    {
        return $this->itemsSchema;
    }
}
