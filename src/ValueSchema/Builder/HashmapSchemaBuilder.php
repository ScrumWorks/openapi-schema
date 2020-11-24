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
    protected ValueSchemaInterface $itemsSchema;

    /**
     * @var string[]
     */
    protected array $requiredProperties = [];

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
    public function withRequiredProperties(array $requiredProperties)
    {
        $this->requiredProperties = $requiredProperties;
        return $this;
    }

    public function getItemsSchema(): ValueSchemaInterface
    {
        return $this->itemsSchema;
    }

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }

    protected function validate(): void
    {
        parent::validate();

        $this->assertRequiredProperty('itemsSchema');
    }

    protected function createInstance(): HashmapSchema
    {
        return new HashmapSchema(
            $this->itemsSchema,
            $this->requiredProperties,
            $this->nullable,
            $this->description,
            $this->example
        );
    }
}
