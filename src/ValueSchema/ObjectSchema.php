<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;

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
        // TODO: maybe $propertiesSchemas as stdClass?

        $this->propertiesSchemas = $propertiesSchemas;
        $this->requiredProperties = $requiredProperties;

        parent::__construct($nullable, $description);
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
