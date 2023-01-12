<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class SchemaParser implements SchemaParserInterface
{
    private SchemaBuilderFactory $schemaBuilderFactory;

    public function __construct(SchemaBuilderFactory $schemaBuilderFactory)
    {
        $this->schemaBuilderFactory = $schemaBuilderFactory;
    }

    public function getEntitySchema(string $class): ValueSchemaInterface
    {
        try {
            return $this->schemaBuilderFactory->createForClass($class)->build();
        } catch (\Throwable $error) {
            throw new LogicException("{$class}: {$error->getMessage()}", previous: $error);
        }
    }

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface
    {
        try {
            return $this->schemaBuilderFactory->createForProperty($propertyReflection)->build();
        } catch (\Throwable $error) {
            $propertyIdentifier = "{$propertyReflection->getDeclaringClass()->getName()}::{$propertyReflection->getName()}";
            throw new LogicException("{$propertyIdentifier}: {$error->getMessage()}", previous: $error);
        }
    }
}
