<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\PropertySchemaDecorator;

use ReflectionClass;
use ReflectionProperty;
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

class SimplePropertySchemaDecorator implements PropertySchemaDecoratorInterface
{
    public function isEnum(ReflectionProperty $propertyReflection): bool
    {
        return false;
    }

    public function decorateValueSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        return $builder;
    }

    public function decorateMixedSchemaBuilder(
        MixedSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): MixedSchemaBuilder {
        return $builder;
    }

    public function decorateIntegerSchemaBuilder(
        IntegerSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): IntegerSchemaBuilder {
        return $builder;
    }

    public function decorateFloatSchemaBuilder(
        FloatSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): FloatSchemaBuilder {
        return $builder;
    }

    public function decorateBooleanSchemaBuilder(
        BooleanSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): BooleanSchemaBuilder {
        return $builder;
    }

    public function decorateStringSchemaBuilder(
        StringSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): StringSchemaBuilder {
        return $builder;
    }

    public function decorateEnumSchemaBuilder(
        EnumSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): EnumSchemaBuilder {
        return $builder;
    }

    public function decorateArraySchemaBuilder(
        ArraySchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): ArraySchemaBuilder {
        return $builder;
    }

    public function decorateHashmapSchemaBuilder(
        HashmapSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): HashmapSchemaBuilder {
        return $builder;
    }

    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflexion
    ): ObjectSchemaBuilder {
        $objectDefaultValues = $classReflexion->getDefaultProperties();
        $requiredProperties = [];
        foreach (\array_keys($builder->getPropertiesSchemas()) as $propertyName) {
            if (! \array_key_exists($propertyName, $objectDefaultValues)) {
                $requiredProperties[] = $propertyName;
            }
        }
        return $builder->withRequiredProperties($requiredProperties);
    }
}