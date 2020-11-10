<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

/**
 * @method HashmapSchema build()
 */
final class HashmapSchemaBuilder extends AbstractSchemaBuilder
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
        return new HashmapSchema($this->itemsSchema, $this->nullable, $this->description);
    }
}
