<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\BooleanSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\FloatSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\IntegerSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\MixedSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\PropertyTypeReaderInterface;
use ScrumWorks\PropertyReader\VariableType\ArrayVariableType;
use ScrumWorks\PropertyReader\VariableType\ClassVariableType;
use ScrumWorks\PropertyReader\VariableType\MixedVariableType;
use ScrumWorks\PropertyReader\VariableType\ScalarVariableType;
use ScrumWorks\PropertyReader\VariableType\UnionVariableType;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;

/**
 * @TODO: probably new name
 */
final class SchemaParser implements SchemaParserInterface
{
    private PropertyTypeReaderInterface $propertyReader;

    public function __construct(PropertyTypeReaderInterface $propertyReader)
    {
        $this->propertyReader = $propertyReader;
    }

    public function getEntitySchema(string $class): ObjectSchema
    {
        $builder = $this->createClassSchemaBuilder($class);
        return $builder->build();
    }

    private function createClassSchemaBuilder(string $class): ObjectSchemaBuilder
    {
        if (! \class_exists($class)) {
            throw new Exception('TODO');
        }

        $reflection = new ReflectionClass($class);

        $propertiesSchemas = [];
        foreach ($reflection->getProperties() as $propertyReflection) {
            // if property is not public, then skip it.
            if (! $propertyReflection->isPublic()) {
                continue;
            }

            $propertiesSchemas[$propertyReflection->getName()] = $this->getPropertySchema($propertyReflection);
        }
        return (new ObjectSchemaBuilder())->withPropertiesSchemas($propertiesSchemas);
    }

    private function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface
    {
        $variableType = $this->propertyReader->readUnifiedVariableType($propertyReflection);
        // TODO
        $annotations = [];
        return $this->translate($variableType, $annotations);
    }

    private function translate(?VariableTypeInterface $variableType, array $annotations): ValueSchemaInterface
    {
        if ($variableType === null) {
            $schemaBuilder = new MixedSchemaBuilder();
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = new MixedSchemaBuilder();
        } elseif ($variableType instanceof ScalarVariableType) {
            switch ($variableType->getType()) {
                case ScalarVariableType::TYPE_INTEGER:
                    $schemaBuilder = new IntegerSchemaBuilder();
                    break;
                case ScalarVariableType::TYPE_FLOAT:
                    $schemaBuilder = new FloatSchemaBuilder();
                    break;
                case ScalarVariableType::TYPE_BOOLEAN:
                    $schemaBuilder = new BooleanSchemaBuilder();
                    break;
                case ScalarVariableType::TYPE_STRING:
                    $schemaBuilder = new StringSchemaBuilder();
                    break;
            }
        } elseif ($variableType instanceof ArrayVariableType) {
            if ($variableType->getKeyType() === null) {
                $schemaBuilder = new ArraySchemaBuilder();
            } else {
                $schemaBuilder = new HashmapSchemaBuilder();
            }
            $schemaBuilder = $schemaBuilder->withItemsSchema($this->translate($variableType->getItemType(), []));
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createClassSchemaBuilder($variableType->getClass());
        } elseif ($variableType instanceof UnionVariableType) {
            throw new Exception('Union types are not supported');
        }

        if (! isset($schemaBuilder)) {
            throw new Exception('TODO');
        }
        if ($schemaBuilder instanceof MixedSchemaBuilder) {
            $schemaBuilder = $schemaBuilder->withNullable(true);
        } else {
            $schemaBuilder = $schemaBuilder->withNullable($variableType->isNullable());
        }

        return $schemaBuilder->build();
    }
}
