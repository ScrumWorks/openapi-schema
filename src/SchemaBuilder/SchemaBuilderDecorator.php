<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
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

class SchemaBuilderDecorator
{
    /**
     * @var PropertySchemaDecoratorInterface[]
     */
    private array $propertyDecorators;

    /**
     * @var ClassSchemaDecoratorInterface[]
     */
    private array $classDecorators;

    /**
     * @param PropertySchemaDecoratorInterface[] $propertyDecorators
     * @param ClassSchemaDecoratorInterface[] $classDecorators
     */
    public function __construct(array $propertyDecorators, array $classDecorators)
    {
        $this->propertyDecorators = $propertyDecorators;
        $this->classDecorators = $classDecorators;
    }

    public function decorateClassSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder {
        foreach ($this->classDecorators as $propertyDecorator) {
            if ($builder instanceof MixedSchemaBuilder) {
                $builder = $propertyDecorator->decorateMixedSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof IntegerSchemaBuilder) {
                $builder = $propertyDecorator->decorateIntegerSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof FloatSchemaBuilder) {
                $builder = $propertyDecorator->decorateFloatSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof BooleanSchemaBuilder) {
                $builder = $propertyDecorator->decorateBooleanSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof StringSchemaBuilder) {
                $builder = $propertyDecorator->decorateStringSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof EnumSchemaBuilder) {
                $builder = $propertyDecorator->decorateEnumSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof ArraySchemaBuilder) {
                $builder = $propertyDecorator->decorateArraySchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof HashmapSchemaBuilder) {
                $builder = $propertyDecorator->decorateHashmapSchemaBuilder($builder, $classReflection);
            } elseif ($builder instanceof ObjectSchemaBuilder) {
                $builder = $propertyDecorator->decorateObjectSchemaBuilder($builder, $classReflection);
            } else {
                throw new LogicException('Unknown schema builder type: ' . \get_class($builder));
            }

            $builder = $propertyDecorator->decorateValueSchemaBuilder($builder, $classReflection);
        }

        return $builder;
    }

    public function decoratePropertySchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        foreach ($this->propertyDecorators as $propertyDecorator) {
            if ($builder instanceof MixedSchemaBuilder) {
                $builder = $propertyDecorator->decorateMixedSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof IntegerSchemaBuilder) {
                $builder = $propertyDecorator->decorateIntegerSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof FloatSchemaBuilder) {
                $builder = $propertyDecorator->decorateFloatSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof BooleanSchemaBuilder) {
                $builder = $propertyDecorator->decorateBooleanSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof StringSchemaBuilder) {
                $builder = $propertyDecorator->decorateStringSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof EnumSchemaBuilder) {
                $builder = $propertyDecorator->decorateEnumSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof ArraySchemaBuilder) {
                $builder = $propertyDecorator->decorateArraySchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof HashmapSchemaBuilder) {
                $builder = $propertyDecorator->decorateHashmapSchemaBuilder($builder, $propertyReflection);
            } elseif ($builder instanceof ObjectSchemaBuilder) {
                $builder = $propertyDecorator->decorateObjectSchemaBuilder($builder, $propertyReflection);
            } else {
                throw new LogicException('Unknown schema builder type: ' . \get_class($builder));
            }

            $builder = $propertyDecorator->decorateValueSchemaBuilder($builder, $propertyReflection);
        }

        return $builder;
    }
}
