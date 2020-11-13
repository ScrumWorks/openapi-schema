<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use DateTimeInterface;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Exception\DomainException;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\PropertySchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\SimplePropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\BooleanSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\EnumSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\FloatSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\IntegerSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\MixedSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\PropertyTypeReaderInterface;
use ScrumWorks\PropertyReader\VariableType\ArrayVariableType;
use ScrumWorks\PropertyReader\VariableType\ClassVariableType;
use ScrumWorks\PropertyReader\VariableType\MixedVariableType;
use ScrumWorks\PropertyReader\VariableType\ScalarVariableType;
use ScrumWorks\PropertyReader\VariableType\UnionVariableType;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;

class SchemaParser implements SchemaParserInterface
{
    protected PropertyTypeReaderInterface $propertyReader;

    protected PropertySchemaDecoratorInterface $propertySchemaDecorator;

    public function __construct(
        PropertyTypeReaderInterface $propertyReader,
        ?PropertySchemaDecoratorInterface $propertySchemaDecorator = null
    ) {
        $this->propertyReader = $propertyReader;
        $propertySchemaDecorator ??= new SimplePropertySchemaDecorator();
        $this->propertySchemaDecorator = $propertySchemaDecorator;
    }

    public function setPropertySchemaDecorator(PropertySchemaDecoratorInterface $propertySchemaDecorator): void
    {
        $this->propertySchemaDecorator = $propertySchemaDecorator;
    }

    public function getEntitySchema(string $class): ValueSchemaInterface
    {
        $builder = $this->createSchemaBuilderFromClass($class, null);
        return $builder->build();
    }

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface
    {
        $variableType = $this->propertyReader->readUnifiedVariableType($propertyReflection);
        return $this->variableTypeToVariableSchema($variableType, $propertyReflection);
    }

    public function getVariableTypeSchema(?VariableTypeInterface $variableType): ValueSchemaInterface
    {
        return $this->variableTypeToVariableSchema($variableType, null);
    }

    protected function variableTypeToVariableSchema(
        ?VariableTypeInterface $variableType,
        ?ReflectionProperty $propertyReflection
    ): ValueSchemaInterface {
        if ($variableType === null) {
            $schemaBuilder = $this->createSchemaBuilderFromMixed();
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromMixed();
        } elseif ($variableType instanceof ScalarVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromScalar($variableType);
        } elseif ($variableType instanceof ArrayVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromArray($variableType);
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromClass($variableType->getClass(), $propertyReflection);
        } elseif ($variableType instanceof UnionVariableType) {
            $schemaBuilder = $this->createSchemaBuilderFromUnion($variableType, $propertyReflection);
        }
        if (! isset($schemaBuilder)) {
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

        $schemaBuilder = $this->decorateSchemaBuilderByType($schemaBuilder, $propertyReflection);

        return $schemaBuilder->build();
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

    protected function createSchemaBuilderFromArray(ArrayVariableType $variableType): AbstractSchemaBuilder
    {
        if ($variableType->getKeyType() === null) {
            $schemaBuilder = new ArraySchemaBuilder();
        } else {
            $schemaBuilder = new HashmapSchemaBuilder();
        }

        return $schemaBuilder->withItemsSchema(
            $this->variableTypeToVariableSchema($variableType->getItemType(), null)
        );
    }

    protected function createSchemaBuilderFromClass(
        string $class,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        if (! \class_exists($class) && ! \interface_exists($class)) {
            throw new LogicException(\sprintf("Class or interface '${class}' doesn't exists"));
        }

        // TODO: maybe move this to decorator?
        if (\is_a($class, DateTimeInterface::class, true)) {
            $schemaBuilder = new StringSchemaBuilder();
            // TODO: move this to some constant?
            $schemaBuilder->withFormat('date-time');
            return $this->decorateSchemaBuilderByType($schemaBuilder, $propertyReflection);
        }

        if (\interface_exists($class)) {
            throw new LogicException(\sprintf("Unprocessable interface '%s' for creating schema", $class));
        }

        if (! \class_exists($class)) {
            throw new LogicException(\sprintf("Class '${class}' doesn't exists"));
        }

        $classReflexion = new ReflectionClass($class);

        $propertiesSchemas = [];
        foreach ($classReflexion->getProperties() as $classPropertyReflection) {
            // if property is not public, then skip it.
            if (! $classPropertyReflection->isPublic()) {
                continue;
            }

            $propertiesSchemas[$classPropertyReflection->getName()] = $this->getPropertySchema(
                $classPropertyReflection
            );
        }

        $schemaBuilder = (new ObjectSchemaBuilder())->withPropertiesSchemas($propertiesSchemas);
        return $this->propertySchemaDecorator->decorateObjectSchemaBuilder(
            $schemaBuilder,
            $classReflexion,
            $propertyReflection
        );
    }

    protected function createSchemaBuilderFromUnion(
        UnionVariableType $unionVariableType,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        throw new DomainException('Union types are not supported');
    }

    protected function decorateSchemaBuilderByType(
        AbstractSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        if ($builder instanceof MixedSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateMixedSchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof IntegerSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateIntegerSchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof FloatSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateFloatSchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof BooleanSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateBooleanSchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof StringSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateStringSchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof EnumSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateEnumSchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof ArraySchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateArraySchemaBuilder($builder, $propertyReflection);
        } elseif ($builder instanceof HashmapSchemaBuilder) {
            $builder = $this->propertySchemaDecorator->decorateHashmapSchemaBuilder($builder, $propertyReflection);
        }

        return $this->propertySchemaDecorator->decorateValueSchemaBuilder($builder, $propertyReflection);
    }
}
