<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder;

use ReflectionClass;
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

interface ClassSchemaDecoratorInterface
{
    public function decorateValueSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateMixedSchemaBuilder(
        MixedSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateIntegerSchemaBuilder(
        IntegerSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateFloatSchemaBuilder(
        FloatSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateBooleanSchemaBuilder(
        BooleanSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateStringSchemaBuilder(
        StringSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateEnumSchemaBuilder(
        EnumSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateArraySchemaBuilder(
        ArraySchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateHashmapSchemaBuilder(
        HashmapSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;

    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;
}
