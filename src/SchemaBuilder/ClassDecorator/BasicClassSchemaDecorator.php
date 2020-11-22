<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\ClassDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\SchemaBuilder\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

class BasicClassSchemaDecorator implements ClassSchemaDecoratorInterface
{
    use ClassSchemaDecoratorDefaultTrait;

    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflexion
    ): AbstractSchemaBuilder {
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
