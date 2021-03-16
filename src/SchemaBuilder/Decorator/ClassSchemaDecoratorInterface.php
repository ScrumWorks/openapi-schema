<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;

interface ClassSchemaDecoratorInterface
{
    public function decorateClassSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder;
}
