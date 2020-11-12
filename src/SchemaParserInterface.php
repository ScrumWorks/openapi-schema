<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;

interface SchemaParserInterface
{
    public function getEntitySchema(string $class): ValueSchemaInterface;

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface;

    public function getVariableTypeSchema(?VariableTypeInterface $variableType): ValueSchemaInterface;
}
