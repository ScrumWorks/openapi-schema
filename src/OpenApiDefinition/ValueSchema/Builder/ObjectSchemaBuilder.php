<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\ObjectSchema;
use Lang\OpenApiDefinition\ValueSchema\ValueSchemaInterface;

/**
 * @method ObjectSchema build()
 */
class ObjectSchemaBuilder extends AbstractSchemaBuilder
{
    protected array $propertiesSchemas = [];
    protected array $requiredProperties = [];

    /**
     * @param array<string, ValueSchemaInterface> $propertiesSchemas
     * @return static
     */
    public function withPropertiesSchemas(array $propertiesSchemas)
    {
        $this->propertiesSchemas = $propertiesSchemas;
        return $this;
    }

    /**
     * @param string[] $requiredProperties
     * @return static
     */
    public function withRequiredProperties(array $requiredProperties)
    {
        $this->requiredProperties = $requiredProperties;
        return $this;
    }

    protected function createInstance(): ObjectSchema
    {
        return new ObjectSchema(
            $this->propertiesSchemas,
            $this->requiredProperties,
            $this->nullable,
            $this->description
        );
    }
}
