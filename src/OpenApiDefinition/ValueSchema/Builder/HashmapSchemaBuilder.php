<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\HashmapSchema;
use Lang\OpenApiDefinition\ValueSchema\ValueSchemaInterface;

/**
 * @method HashmapSchema build()
 */
class HashmapSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?ValueSchemaInterface $itemsSchema = null;

    /**
     * @return static
     */
    public function withItemsSchema(?ValueSchemaInterface $itemsSchema)
    {
        $this->itemsSchema = $itemsSchema;
        return $this;
    }

    protected function createInstance(): HashmapSchema
    {
        return new HashmapSchema(
            $this->itemsSchema,
            $this->nullable,
            $this->description
        );
    }
}
