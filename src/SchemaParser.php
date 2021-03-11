<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class SchemaParser implements SchemaParserInterface
{
    private SchemaBuilderFactory $schemaBuilderFactory;

    public function __construct(SchemaBuilderFactory $schemaBuilderFactory)
    {
        $this->schemaBuilderFactory = $schemaBuilderFactory;
    }

    public function getEntitySchema(string $class, ClassReferenceBag $referenceBag): ValueSchemaInterface
    {
        return $this->schemaBuilderFactory->createForClass($class, $referenceBag)->build();
    }

    public function getPropertySchema(
        ReflectionProperty $propertyReflection,
        ClassReferenceBag $referenceBag
    ): ValueSchemaInterface {
        return $this->schemaBuilderFactory->createForProperty($propertyReflection, $referenceBag)->build();
    }
}
