<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ObjectSchemaData;
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
     */
    public function withPropertiesSchemaBuilders(array $propertiesSchemaBuilders): self
    {
        $this->propertiesSchemaBuilders = $propertiesSchemaBuilders;
        return $this;
    }

    /**
     * @param string[] $requiredProperties
     */
    public function withRequiredProperties(array $requiredProperties): self
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
        $propertySchemas = [];
        foreach ($this->propertiesSchemaBuilders as $propertyName => $builder) {
            try {
                $propertySchemas[$propertyName] = $builder->build();
            } catch (\Throwable $error) {
                throw new LogicException("property `{$propertyName}`: {$error->getMessage()}", previous: $error);
            }
        }

        return new ObjectSchemaData(
            $propertySchemas,
            $this->requiredProperties,
            $this->nullable,
            $this->description,
            $this->schemaName,
            $this->deprecated
        );
    }
}
