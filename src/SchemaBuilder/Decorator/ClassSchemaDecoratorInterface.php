<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

interface ClassSchemaDecoratorInterface
{
    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): ObjectSchemaBuilder;
}
