<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface SchemaParserInterface
{
    public function getEntitySchema(string $class, ClassReferenceBag $referenceBag): ValueSchemaInterface;

    public function getPropertySchema(
        ReflectionProperty $propertyReflection,
        ClassReferenceBag $referenceBag
    ): ValueSchemaInterface;
}
