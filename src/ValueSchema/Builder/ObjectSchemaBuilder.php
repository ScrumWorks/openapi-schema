<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class ObjectSchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * @var array<string, ValueSchemaInterface>
     */
    protected array $propertiesSchemas = [];

    /**
     * @var array<string, AbstractSchemaBuilder>
     */
    protected array $propertiesSchemaBuilders = [];

    /**
     * @var string[]
     */
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
     * @param array<string, AbstractSchemaBuilder> $propertiesSchemaBuilders
     * @return static
     */
    public function withPropertiesSchemaBuilders(array $propertiesSchemaBuilders)
    {
        $this->propertiesSchemaBuilders = $propertiesSchemaBuilders;
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

    /**
     * @return array<string, ValueSchemaInterface>
     */
    public function getPropertiesSchemas(): array
    {
        return $this->propertiesSchemas;
    }

    /**
     * @return array<string, AbstractSchemaBuilder>
     */
    public function getPropertiesSchemaBuilders(): array
    {
        return $this->propertiesSchemaBuilders;
    }

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }

    public function build(): ObjectSchema
    {
        $propertySchemas = $this->propertiesSchemas;
        foreach ($this->propertiesSchemaBuilders as $propertyName => $propertiesSchemaBuilder) {
            if (isset($propertySchemas[$propertyName])) {
                throw new LogicException(
                    "There are both schema and schemaBuilder defined for property '${propertyName}'."
                );
            }

            $propertySchemas[$propertyName] = $propertiesSchemaBuilder->build();
        }

        return new ObjectSchema($propertySchemas, $this->requiredProperties, $this->nullable, $this->description);
    }
}
