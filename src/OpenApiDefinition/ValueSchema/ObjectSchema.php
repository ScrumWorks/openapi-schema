<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema;

/**
 * @property-read ValueSchemaInterface[] $propertiesSchemas
 * @property-read string[] $requiredProperties
 */
class ObjectSchema extends AbstractValueSchema
{
    protected array $propertiesSchemas;
    protected array $requiredProperties;

    /**
     * @param array<string, ValueSchemaInterface> $propertiesSchemas
     * @param array<string> $requiredProperties
     */
    public function __construct(
        array $propertiesSchemas,
        array $requiredProperties,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        // TODO assert that $propertiesSchemas are all of ValueSchemaInterface instance
        $this->propertiesSchemas = $propertiesSchemas;
        // TODO assert that $requiredProperties values are in $propertiesSchemas keys
        $this->requiredProperties = $requiredProperties;
    }

    public function getPropertiesSchemas(): array
    {
        return $this->propertiesSchemas;
    }

    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }
}
