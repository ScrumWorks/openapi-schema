<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

/**
 * @method ObjectSchema build()
 */
final class ObjectSchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * @var array<string, ValueSchemaInterface>
     */
    private array $propertiesSchemas = [];

    /**
     * @var string[]
     */
    private array $requiredProperties = [];

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
