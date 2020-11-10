<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class ArraySchema extends AbstractValueSchema
{
    private ValueSchemaInterface $itemsSchema;

    private ?int $minItems;

    private ?int $maxItems;

    private ?bool $uniqueItems;

    public function __construct(
        ValueSchemaInterface $itemsSchema,
        ?int $minItems,
        ?int $maxItems,
        ?bool $uniqueItems,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        $this->itemsSchema = $itemsSchema;
        $this->minItems = $minItems;
        $this->maxItems = $maxItems;
        $this->uniqueItems = $uniqueItems;
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

    public function getItemsSchema(): ValueSchemaInterface
    {
        return $this->itemsSchema;
    }
}
