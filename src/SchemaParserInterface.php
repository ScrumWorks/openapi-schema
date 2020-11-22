<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface SchemaParserInterface
{
    public function getEntitySchema(string $class): ValueSchemaInterface;

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface;
}
