<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class ArraySchemaData extends AbstractValueSchema implements ArraySchema
{
    public function __construct(
        private readonly ValueSchemaInterface $itemsSchema,
        private readonly ?int $minItems = null,
        private readonly ?int $maxItems = null,
        private readonly ?bool $uniqueItems = null,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated);
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
