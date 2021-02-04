<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

final class ObjectSchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * @var array<string, AbstractSchemaBuilder>
     */
    protected array $propertiesSchemaBuilders = [];

    /**
     * @var string[]
     */
    protected array $requiredProperties = [];

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
        $propertySchemas = \array_map(
            static fn (AbstractSchemaBuilder $builder) => $builder->build(),
            $this->propertiesSchemaBuilders
        );

        return new ObjectSchema($propertySchemas, $this->requiredProperties, $this->nullable, $this->description);
    }
}
