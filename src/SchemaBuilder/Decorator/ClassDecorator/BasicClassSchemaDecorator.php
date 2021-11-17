<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

final class BasicClassSchemaDecorator implements ClassSchemaDecoratorInterface
{
    public function decorateClassSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionClass $classReflexion
    ): AbstractSchemaBuilder {
        if (! $builder instanceof ObjectSchemaBuilder) {
            return $builder;
        }

        $objectDefaultValues = $classReflexion->getDefaultProperties();
        $requiredProperties = [];
        foreach (array_keys($builder->getPropertiesSchemaBuilders()) as $propertyName) {
            if (! \array_key_exists($propertyName, $objectDefaultValues)) {
                $requiredProperties[] = $propertyName;
            }
        }

        return $builder->withRequiredProperties($requiredProperties);
    }
}
