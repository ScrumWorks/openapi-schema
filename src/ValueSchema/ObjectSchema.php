<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface ObjectSchema extends ValueSchemaInterface
{
    /**
     * @return array<string, ValueSchemaInterface>
     */
    public function getPropertiesSchemas(): array;

    public function getPropertySchema(string $property): ValueSchemaInterface;

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array;
}
