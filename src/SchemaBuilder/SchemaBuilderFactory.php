<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\ClassReferenceBag;
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

    public function createForClass(string $class, ClassReferenceBag $referenceBag): AbstractSchemaBuilder
    {
        if (! \class_exists($class) && ! \interface_exists($class)) {
            throw new LogicException("Class or interface '${class}' does not exist");
        }

        $classReflection = new ReflectionClass($class);
        return $this->createSchemaBuilderFromClass($classReflection, $referenceBag);
    }

    public function createForProperty(
        ReflectionProperty $propertyReflection,
        ClassReferenceBag $referenceBag
    ): AbstractSchemaBuilder {
        $variableType = $this->propertyReader->readUnifiedVariableType($propertyReflection);
        return $this->schemaBuilderDecorator->decoratePropertySchemaBuilder(
            $this->createForVariableType($variableType, $referenceBag),
            $propertyReflection
        );
    }

    protected function createForVariableType(
        ?VariableTypeInterface $variableType,
        ClassReferenceBag $referenceBag
    ): AbstractSchemaBuilder {
        if ($variableType === null) {
            $schemaBuilder = $this->createSchemaBuilderFromMixed();
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromMixed();
        } elseif ($variableType instanceof ScalarVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromScalar($variableType);
        } elseif ($variableType instanceof ArrayVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromArray($variableType, $referenceBag);
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createForClass($variableType->getClass(), $referenceBag);
        } elseif ($variableType instanceof UnionVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromUnion($variableType, $referenceBag);
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
            default:
                throw new LogicException(\sprintf("Unknown scalar type '%s'", $variableType->getType()));
        }

        return $schemaBuilder;
    }

    protected function createSchemaBuilderFromArray(
        ArrayVariableType $variableType,
        ClassReferenceBag $referenceBag
    ): AbstractSchemaBuilder {
        if ($variableType->getKeyType() === null) {
            $schemaBuilder = new ArraySchemaBuilder();
        } else {
            $schemaBuilder = new HashmapSchemaBuilder();
        }

        return $schemaBuilder->withItemsSchemaBuilder(
            $this->createForVariableType($variableType->getItemType(), $referenceBag)
        );
    }

    protected function createSchemaBuilderFromClass(
        ReflectionClass $classReflection,
        ClassReferenceBag $referenceBag
    ): AbstractSchemaBuilder {
        if ($referenceBag->hasClassBuilder($classReflection->getName())) {
            return $referenceBag->getClassBuilderReference($classReflection->getName());
        }

        $builder = (new ObjectSchemaBuilder());

        $referenceBag->addClassBuilder(
            $classReflection->getName(),
            $builder
        // we must add builder to bag before processing properties
        );

        $propertiesSchemas = [];
        foreach ($classReflection->getProperties() as $propertyReflection) {
            // if property is not public, then skip it.
            if (! $propertyReflection->isPublic()) {
                continue;
            }

            $propertiesSchemas[$propertyReflection->getName()] = $this->createForProperty(
                $propertyReflection,
                $referenceBag
            );
        }

        $builder->withPropertiesSchemaBuilders($propertiesSchemas);

        $builder = $this->schemaBuilderDecorator->decorateClassSchemaBuilder($builder, $classReflection);

        // update builder in reference bag after decoration
        $referenceBag->addClassBuilder($classReflection->getName(), $builder);

        return $referenceBag->getClassBuilderReference($classReflection->getName());
    }

    protected function createSchemaBuilderFromUnion(
        UnionVariableType $variableType,
        ClassReferenceBag $referenceBag
    ): AbstractSchemaBuilder {
        return new UnionSchemaBuilder(\array_map(
            fn (VariableTypeInterface $type) => $this->createForVariableType($type, $referenceBag),
            $variableType->getTypes()
        ));
    }
}
