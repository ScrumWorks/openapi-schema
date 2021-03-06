<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;

final class ArraySchemaBuilder extends AbstractSchemaBuilder
{
    protected ?AbstractSchemaBuilder $itemsSchemaBuilder = null;

    protected ?int $minItems = null;

    protected ?int $maxItems = null;

    protected ?bool $uniqueItems = null;

    /**
     * @return static
     */
    public function withItemsSchemaBuilder(?AbstractSchemaBuilder $itemsSchemaBuilder)
    {
        $this->itemsSchemaBuilder = $itemsSchemaBuilder;
        return $this;
    }

    /**
     * @return static
     */
    public function withMinItems(?int $minItems)
    {
        $this->minItems = $minItems;
        return $this;
    }

    /**
     * @return static
     */
    public function withMaxItems(?int $maxItems)
    {
        $this->maxItems = $maxItems;
        return $this;
    }

    /**
     * @return static
     */
    public function withUniqueItems(?bool $uniqueItems)
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

        return new ArraySchema(
            $this->itemsSchemaBuilder->build(),
            $this->minItems,
            $this->maxItems,
            $this->uniqueItems,
            $this->nullable,
            $this->description,
            $this->schemaName
        );
    }
}
