<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use Exception;

final class ObjectSchema extends AbstractValueSchema
{
    /**
     * @var array<string, ValueSchemaInterface>
     */
    private array $propertiesSchemas;

    /**
     * @var string[]
     */
    private array $requiredProperties;

    /**
     * @param array<string, ValueSchemaInterface> $propertiesSchemas
     * @param string[] $requiredProperties
     */
    public function __construct(
        array $propertiesSchemas,
        array $requiredProperties = [],
        bool $nullable = false,
        ?string $description = null
    ) {
        parent::__construct($nullable, $description);

        // TODO assert that $propertiesSchemas are all of ValueSchemaInterface instance
        $this->propertiesSchemas = $propertiesSchemas;
        // TODO assert that $requiredProperties values are in $propertiesSchemas keys
        $this->requiredProperties = $requiredProperties;
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
            throw new Exception('TODO');
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
}
