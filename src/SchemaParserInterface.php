<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\SchemaCollection\IClassSchemaCollection;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface SchemaParserInterface
{
    public function getEntitySchema(string $class, IClassSchemaCollection $classSchemaCollection): ValueSchemaInterface;

    public function getPropertySchema(
        ReflectionProperty $propertyReflection,
        IClassSchemaCollection $classSchemaCollection
    ): ValueSchemaInterface;
}
