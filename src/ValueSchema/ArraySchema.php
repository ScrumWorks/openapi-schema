<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;

final class ArraySchema extends AbstractValueSchema
{
    private ValueSchemaInterface $itemsSchema;

    private ?int $minItems;

    private ?int $maxItems;

    private ?bool $uniqueItems;

    public function __construct(
        ValueSchemaInterface $itemsSchema,
        ?int $minItems = null,
        ?int $maxItems = null,
        ?bool $uniqueItems = null,
        bool $nullable = false,
        ?string $description = null,
        $example = null
    ) {
        $this->itemsSchema = $itemsSchema;
        $this->minItems = $minItems;
        $this->maxItems = $maxItems;
        $this->uniqueItems = $uniqueItems;

        parent::__construct($nullable, $description, $example);
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

    protected function validate(): void
    {
        if ($this->minItems !== null && $this->minItems < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'minItems'", $this->minItems));
        }
        if ($this->maxItems !== null && $this->maxItems < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maxItems'", $this->maxItems));
        }
        if ($this->minItems !== null && $this->maxItems !== null && $this->maxItems < $this->minItems) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maxItems'", $this->maxItems));
        }
    }
}
