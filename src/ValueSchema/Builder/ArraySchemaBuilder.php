<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ArraySchemaData;

final class ArraySchemaBuilder extends AbstractSchemaBuilder
{
    protected ?AbstractSchemaBuilder $itemsSchemaBuilder = null;

    protected ?int $minItems = null;

    protected ?int $maxItems = null;

    protected ?bool $uniqueItems = null;

    public function withItemsSchemaBuilder(?AbstractSchemaBuilder $itemsSchemaBuilder): self
    {
        $this->itemsSchemaBuilder = $itemsSchemaBuilder;
        return $this;
    }

    public function withMinItems(?int $minItems): self
    {
        $this->minItems = $minItems;
        return $this;
    }

    public function withMaxItems(?int $maxItems): self
    {
        $this->maxItems = $maxItems;
        return $this;
    }

    public function withUniqueItems(?bool $uniqueItems): self
    {
        $this->uniqueItems = $uniqueItems;
        return $this;
    }

    public function getItemsSchemaBuilder(): ?AbstractSchemaBuilder
    {
        return $this->itemsSchemaBuilder;
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

    public function build(): ArraySchema
    {
        if ($this->itemsSchemaBuilder === null) {
            throw new LogicException("'itemsSchemaBuilder' has to be set.");
        }

        try {
            $itemsSchema = $this->itemsSchemaBuilder->build();
        } catch (\Throwable $error) {
            throw new LogicException("items: {$error->getMessage()}", previous: $error);
        }

        return new ArraySchemaData(
            $itemsSchema,
            $this->minItems,
            $this->maxItems,
            $this->uniqueItems,
            $this->nullable,
            $this->description,
            $this->schemaName,
            $this->deprecated
        );
    }
}
