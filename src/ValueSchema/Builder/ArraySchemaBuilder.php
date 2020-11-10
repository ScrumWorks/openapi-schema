<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

/**
 * @method ArraySchema build()
 */
final class ArraySchemaBuilder extends AbstractSchemaBuilder
{
    protected ValueSchemaInterface $itemsSchema;

    protected ?int $minItems = null;

    protected ?int $maxItems = null;

    protected ?bool $uniqueItems = null;

    /**
     * @return static
     */
    public function withItemsSchema(ValueSchemaInterface $itemsSchema)
    {
        $this->itemsSchema = $itemsSchema;
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

    protected function validate(): void
    {
        parent::validate();

        $this->assertRequiredProperty('itemsSchema');
    }

    protected function createInstance(): ArraySchema
    {
        return new ArraySchema(
            $this->itemsSchema,
            $this->minItems,
            $this->maxItems,
            $this->uniqueItems,
            $this->nullable,
            $this->description
        );
    }
}
