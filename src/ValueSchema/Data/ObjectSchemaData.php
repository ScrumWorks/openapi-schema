<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class ObjectSchemaData extends AbstractValueSchema implements ObjectSchema
{
    /**
     * @param array<string, ValueSchemaInterface> $propertiesSchemas
     * @param string[] $requiredProperties
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly array $propertiesSchemas,
        private readonly array $requiredProperties = [],
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false,
        array $metaData = [],
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated, $metaData);
    }

    /**
     * @return array<string, ValueSchemaInterface>
     */
    public function getPropertiesSchemas(): array
    {
        return $this->propertiesSchemas;
    }

    public function getPropertySchema(string $property): ValueSchemaInterface
    {
        if (! isset($this->propertiesSchemas[$property])) {
            throw new InvalidArgumentException(\sprintf("Property '%s' doesn't exists", $property));
        }
        return $this->propertiesSchemas[$property];
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
        $properties = [];
        foreach ($this->propertiesSchemas as $property => $schema) {
            if (! \is_string($property)) {
                throw new InvalidArgumentException(\sprintf("Property key '%s' must be string", $property));
            }
            if (! ($schema instanceof ValueSchemaInterface)) {
                throw new InvalidArgumentException(\sprintf(
                    'Invalid schema (must be instance of %s)',
                    ValueSchemaInterface::class
                ));
            }
            $properties[] = $property;
        }

        $excludingRequiredProperties = \array_diff($this->requiredProperties, $properties);
        if ($excludingRequiredProperties) {
            throw new InvalidArgumentException(\sprintf(
                'Required properties are not listed in schema (%s)',
                \implode(', ', $excludingRequiredProperties)
            ));
        }
    }
}
