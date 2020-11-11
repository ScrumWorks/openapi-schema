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

interface PropertySchemaDecoratorInterface
{
    public function isEnum(ReflectionProperty $propertyReflection): bool;

    public function decorateValueSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder;

    public function decorateMixedSchemaBuilder(
        MixedSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): MixedSchemaBuilder;

    public function decorateIntegerSchemaBuilder(
        IntegerSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): IntegerSchemaBuilder;

    public function decorateFloatSchemaBuilder(
        FloatSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): FloatSchemaBuilder;

    public function decorateBooleanSchemaBuilder(
        BooleanSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): BooleanSchemaBuilder;

    public function decorateStringSchemaBuilder(
        StringSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): StringSchemaBuilder;

    public function decorateEnumSchemaBuilder(
        EnumSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): EnumSchemaBuilder;

    public function decorateArraySchemaBuilder(
        ArraySchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): ArraySchemaBuilder;

    public function decorateHashmapSchemaBuilder(
        HashmapSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): HashmapSchemaBuilder;

    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflexion
    ): ObjectSchemaBuilder;
}
