<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\BooleanSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\FloatSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\IntegerSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\MixedSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\UnionSchemaBuilder;
use ScrumWorks\PropertyReader\PropertyTypeReaderInterface;
use ScrumWorks\PropertyReader\VariableType\ArrayVariableType;
use ScrumWorks\PropertyReader\VariableType\ClassVariableType;
use ScrumWorks\PropertyReader\VariableType\MixedVariableType;
use ScrumWorks\PropertyReader\VariableType\Scalar\BooleanVariableType;
use ScrumWorks\PropertyReader\VariableType\Scalar\FloatVariableType;
use ScrumWorks\PropertyReader\VariableType\Scalar\IntegerVariableType;
use ScrumWorks\PropertyReader\VariableType\Scalar\StringVariableType;
use ScrumWorks\PropertyReader\VariableType\ScalarVariableType;
use ScrumWorks\PropertyReader\VariableType\UnionVariableType;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;

class SchemaBuilderFactory
{
    protected PropertyTypeReaderInterface $propertyReader;

    protected SchemaBuilderDecorator $schemaBuilderDecorator;

    public function __construct(
        PropertyTypeReaderInterface $propertyReader,
        SchemaBuilderDecorator $schemaBuilderDecorator
    ) {
        $this->propertyReader = $propertyReader;
        $this->schemaBuilderDecorator = $schemaBuilderDecorator;
    }

    public function createForClass(string $class): AbstractSchemaBuilder
    {
        if (! \class_exists($class) && ! \interface_exists($class)) {
            throw new LogicException("Class or interface '{$class}' does not exist");
        }

        $classReflection = new ReflectionClass($class);
        return $this->schemaBuilderDecorator->decorateClassSchemaBuilder(
            $this->createSchemaBuilderFromClass($classReflection),
            $classReflection
        );
    }

    public function createForProperty(ReflectionProperty $propertyReflection): AbstractSchemaBuilder
    {
        $variableType = $this->propertyReader->readUnifiedVariableType($propertyReflection);
        return $this->schemaBuilderDecorator->decoratePropertySchemaBuilder(
            $this->createForVariableType($variableType),
            $propertyReflection
        );
    }

    protected function createForVariableType(?VariableTypeInterface $variableType): AbstractSchemaBuilder
    {
        if ($variableType === null) {
            $schemaBuilder = $this->createSchemaBuilderFromMixed();
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromMixed();
        } elseif ($variableType instanceof ScalarVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromScalar($variableType);
        } elseif ($variableType instanceof ArrayVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromArray($variableType);
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createForClass($variableType->getClass());
        } elseif ($variableType instanceof UnionVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromUnion($variableType);
        } else {
            throw new LogicException(\sprintf(
                "Unprocessable VariableTypeInterface '%s' (class %s)",
                $variableType->getTypeName(),
                \get_class($variableType)
            ));
        }

        if ($variableType === null) {
            $schemaBuilder = $schemaBuilder->withNullable(true);
        } else {
            $schemaBuilder = $schemaBuilder->withNullable($variableType->isNullable());
        }

        return $schemaBuilder;
    }

    protected function createSchemaBuilderFromMixed(): MixedSchemaBuilder
    {
        return new MixedSchemaBuilder();
    }

    protected function createSchemaBuilderFromScalar(ScalarVariableType $variableType): AbstractSchemaBuilder
    {
        if ($variableType instanceof IntegerVariableType) {
            return new IntegerSchemaBuilder();
        } elseif ($variableType instanceof FloatVariableType) {
            return new FloatSchemaBuilder();
        } elseif ($variableType instanceof BooleanVariableType) {
            return new BooleanSchemaBuilder();
        } elseif ($variableType instanceof StringVariableType) {
            $schemaBuilder = new StringSchemaBuilder();
            if (! $variableType->canBeEmpty()) {
                $schemaBuilder->withMinLength(1);
            }
            return $schemaBuilder;
        }

        throw new LogicException(\sprintf("Unknown scalar type '%s'", $variableType->getTypeName()));
    }

    protected function createSchemaBuilderFromArray(ArrayVariableType $variableType): AbstractSchemaBuilder
    {
        if ($variableType->getKeyType() === null) {
            $schemaBuilder = new ArraySchemaBuilder();
        } else {
            $schemaBuilder = new HashmapSchemaBuilder();
        }

        return $schemaBuilder->withItemsSchemaBuilder($this->createForVariableType($variableType->getItemType()));
    }

    protected function createSchemaBuilderFromClass(ReflectionClass $classReflection): ObjectSchemaBuilder
    {
        $propertiesSchemas = [];
        foreach ($classReflection->getProperties() as $propertyReflection) {
            // if property is not public, then skip it.
            if (! $propertyReflection->isPublic()) {
                continue;
            }

            $propertiesSchemas[$propertyReflection->getName()] = $this->createForProperty($propertyReflection);
        }

        return (new ObjectSchemaBuilder())
            ->withPropertiesSchemaBuilders($propertiesSchemas);
    }

    protected function createSchemaBuilderFromUnion(UnionVariableType $variableType): AbstractSchemaBuilder
    {
        return new UnionSchemaBuilder(\array_map(
            fn (VariableTypeInterface $type) => $this->createForVariableType($type),
            $variableType->getTypes()
        ));
    }
}
