<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\SchemaCollection\IClassSchemaCollection;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class SchemaParser implements SchemaParserInterface
{
    private SchemaBuilderFactory $schemaBuilderFactory;

    public function __construct(SchemaBuilderFactory $schemaBuilderFactory)
    {
        $this->schemaBuilderFactory = $schemaBuilderFactory;
    }

    public function getEntitySchema(string $class, IClassSchemaCollection $classSchemaCollection): ValueSchemaInterface
    {
        return $this->schemaBuilderFactory->createForClass($class, $classSchemaCollection)->build();
    }

    public function getPropertySchema(
        ReflectionProperty $propertyReflection,
        IClassSchemaCollection $classSchemaCollection
    ): ValueSchemaInterface {
        return $this->schemaBuilderFactory->createForProperty($propertyReflection, $classSchemaCollection)->build();
    }
}
