<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

/**
 * @property-read ValueSchemaInterface $itemsSchema
 * @property-read ?int $minItems
 * @property-read ?int $maxItems
 * @property-read ?bool $uniqueItems
 */
class ArraySchema extends AbstractValueSchema
{
    protected ValueSchemaInterface $itemsSchema;

    protected ?int $minItems;

    protected ?int $maxItems;

    protected ?bool $uniqueItems;

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

    protected function getItemsSchema(): ValueSchemaInterface
    {
        return $this->itemsSchema;
    }
}
