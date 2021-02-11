<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\SchemaCollection\IClassSchemaCollection;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;

interface PropertySchemaDecoratorInterface
{
    public function decoratePropertySchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection,
        IClassSchemaCollection $classSchemaCollection
    ): AbstractSchemaBuilder;
}
