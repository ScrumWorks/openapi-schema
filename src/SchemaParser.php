<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use DomainException;
use LogicException;
use ReflectionClass;
use ReflectionProperty;
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
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\PropertyTypeReaderInterface;
use ScrumWorks\PropertyReader\VariableType\ArrayVariableType;
use ScrumWorks\PropertyReader\VariableType\ClassVariableType;
use ScrumWorks\PropertyReader\VariableType\MixedVariableType;
use ScrumWorks\PropertyReader\VariableType\ScalarVariableType;
use ScrumWorks\PropertyReader\VariableType\UnionVariableType;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;

final class SchemaParser implements SchemaParserInterface
{
    private PropertyTypeReaderInterface $propertyReader;

    private PropertySchemaDecoratorInterface $propertySchemaDecorator;

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

    public function getEntitySchema(string $class): ObjectSchema
    {
        $builder = $this->createClassSchemaBuilder($class, null);
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

    private function variableTypeToVariableSchema(
        ?VariableTypeInterface $variableType,
        ?ReflectionProperty $propertyReflexion
    ): ValueSchemaInterface {
        if ($variableType === null) {
            $schemaBuilder = $this->createMixedSchemaBuilder($propertyReflexion);
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = $this->createMixedSchemaBuilder($propertyReflexion);
        } elseif ($variableType instanceof ScalarVariableType) {
            switch ($variableType->getType()) {
                case ScalarVariableType::TYPE_INTEGER:
                    $schemaBuilder = $this->createIntegerSchemaBuilder($propertyReflexion);
                    break;
                case ScalarVariableType::TYPE_FLOAT:
                    $schemaBuilder = $this->createFloatSchemaBuilder($propertyReflexion);
                    break;
                case ScalarVariableType::TYPE_BOOLEAN:
                    $schemaBuilder = $this->createBooleanSchemaBuilder($propertyReflexion);
                    break;
                case ScalarVariableType::TYPE_STRING:
                    if ($propertyReflexion && $this->propertySchemaDecorator->isEnum($propertyReflexion)) {
                        $schemaBuilder = $this->createEnumSchemaBuilder($propertyReflexion);
                    } else {
                        $schemaBuilder = $this->createStringSchemaBuilder($propertyReflexion);
                    }
                    break;
            }
        } elseif ($variableType instanceof ArrayVariableType) {
            if ($variableType->getKeyType() === null) {
                $schemaBuilder = $this->createArraySchemaBuilder($propertyReflexion);
            } else {
                $schemaBuilder = $this->createHashmapSchemaBuilder($propertyReflexion);
            }
            $schemaBuilder = $schemaBuilder->withItemsSchema(
                $this->variableTypeToVariableSchema($variableType->getItemType(), null)
            );
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createClassSchemaBuilder($variableType->getClass(), $propertyReflexion);
        } elseif ($variableType instanceof UnionVariableType) {
            $schemaBuilder = $this->createUnionSchemaBuilder($variableType, $propertyReflexion);
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

        if ($propertyReflexion) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateValueSchemaBuilder(
                $schemaBuilder,
                $propertyReflexion
            );
        }

        return $schemaBuilder->build();
    }

    private function createMixedSchemaBuilder(?ReflectionProperty $propertyReflection): MixedSchemaBuilder
    {
        $schemaBuilder = new MixedSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateMixedSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createIntegerSchemaBuilder(?ReflectionProperty $propertyReflection): IntegerSchemaBuilder
    {
        $schemaBuilder = new IntegerSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateIntegerSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createFloatSchemaBuilder(?ReflectionProperty $propertyReflection): FloatSchemaBuilder
    {
        $schemaBuilder = new FloatSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateFloatSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createBooleanSchemaBuilder(?ReflectionProperty $propertyReflection): BooleanSchemaBuilder
    {
        $schemaBuilder = new BooleanSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateBooleanSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createStringSchemaBuilder(?ReflectionProperty $propertyReflection): AbstractSchemaBuilder
    {
        $schemaBuilder = new StringSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateStringSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createEnumSchemaBuilder(?ReflectionProperty $propertyReflection): AbstractSchemaBuilder
    {
        $schemaBuilder = new EnumSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateEnumSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createArraySchemaBuilder(?ReflectionProperty $propertyReflection): ArraySchemaBuilder
    {
        $schemaBuilder = new ArraySchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateArraySchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createHashmapSchemaBuilder(?ReflectionProperty $propertyReflection): HashmapSchemaBuilder
    {
        $schemaBuilder = new HashmapSchemaBuilder();
        if ($propertyReflection) {
            $schemaBuilder = $this->propertySchemaDecorator->decorateHashmapSchemaBuilder(
                $schemaBuilder,
                $propertyReflection
            );
        }
        return $schemaBuilder;
    }

    private function createClassSchemaBuilder(
        string $class,
        ?ReflectionProperty $propertyReflection
    ): ObjectSchemaBuilder {
        if (! \class_exists($class)) {
            throw new LogicException(\sprintf("Class '${class}' doesn't exists"));
        }

        $classReflexion = new ReflectionClass($class);

        $propertiesSchemas = [];
        foreach ($classReflexion->getProperties() as $classPropertyReflexion) {
            // if property is not public, then skip it.
            if (! $classPropertyReflexion->isPublic()) {
                continue;
            }

            $propertiesSchemas[$classPropertyReflexion->getName()] = $this->getPropertySchema($classPropertyReflexion);
        }

        $schemaBuilder = (new ObjectSchemaBuilder())->withPropertiesSchemas($propertiesSchemas);
        return $this->propertySchemaDecorator->decorateObjectSchemaBuilder($schemaBuilder, $classReflexion);
    }

    private function createUnionSchemaBuilder(
        UnionVariableType $unionVariableType,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        throw new DomainException('Union types are not supported');
    }
}
