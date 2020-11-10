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
     * @var string[]
     */
    protected array $requiredProperties;

    /**
     * @return static
     */
    public function withItemsSchema(?ValueSchemaInterface $itemsSchema)
    {
        $this->itemsSchema = $itemsSchema;
        return $this;
    }

    /**
     * @return static
     */
    public function withRequiredProperties(array $requiredProperties)
    {
        $this->requiredProperties = $requiredProperties;
        return $this;
    }

    protected function createInstance(): HashmapSchema
    {
        return new HashmapSchema(
            $this->itemsSchema,
            $this->requiredProperties,
            $this->nullable,
            $this->description
        );
    }
}
