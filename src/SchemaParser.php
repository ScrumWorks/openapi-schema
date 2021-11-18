<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

/**
 * @see \ScrumWorks\OpenApiSchema\Tests\SchemaParser\SchemaParserTest
 */
final class SchemaParser implements SchemaParserInterface
{
    private SchemaBuilderFactory $schemaBuilderFactory;

    public function __construct(SchemaBuilderFactory $schemaBuilderFactory)
    {
        $this->schemaBuilderFactory = $schemaBuilderFactory;
    }

    public function getEntitySchema(string $class): ValueSchemaInterface
    {
        return $this->schemaBuilderFactory->createForClass($class)->build();
    }

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface
    {
        return $this->schemaBuilderFactory->createForProperty($propertyReflection)->build();
    }
}
