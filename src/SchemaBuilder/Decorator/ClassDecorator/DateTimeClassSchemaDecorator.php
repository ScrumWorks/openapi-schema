<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator;

use DateTimeInterface;
use ReflectionClass;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;

final class DateTimeClassSchemaDecorator implements ClassSchemaDecoratorInterface
{
    public function decorateClassSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder {
        if (is_a($classReflection->getName(), DateTimeInterface::class, true)) {
            return (new StringSchemaBuilder())
                ->withNullable($builder->isNullable())
                ->withSchemaName($builder->getSchemaName())
                ->withDescription($builder->getDescription())
                ->withFormat('date-time');
        }

        return $builder;
    }
}
