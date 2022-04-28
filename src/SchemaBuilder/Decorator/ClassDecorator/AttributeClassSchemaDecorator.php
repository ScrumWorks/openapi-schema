<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Attribute as OA;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\AbstractAttributeSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

final class AttributeClassSchemaDecorator extends AbstractAttributeSchemaDecorator implements ClassSchemaDecoratorInterface
{
    public function decorateClassSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder {
        if (! $builder instanceof ObjectSchemaBuilder) {
            return $builder;
        }

        $objectDefaultValues = $classReflection->getDefaultProperties();
        $requiredProperties = [];
        foreach ($classReflection->getProperties() as $propertyReflection) {
            if (
                $propertyReflection->isPublic()
                && $this->isPropertyRequired($propertyReflection, $objectDefaultValues)
            ) {
                $requiredProperties[] = $propertyReflection->getName();
            }
        }

        $classAttributes = $this->getClassAttributes($classReflection);
        $componentSchema = $this->findAttribute($classAttributes, OA\ComponentSchema::class, true);
        if ($componentSchema && $componentSchema->getSchemaName()) {
            $builder = $builder->withSchemaName($componentSchema->getSchemaName());
        }

        return $builder->withRequiredProperties($requiredProperties);
    }

    private function isPropertyRequired(ReflectionProperty $propertyReflection, array $objectDefaultValues): bool
    {
        $attributes = $this->getPropertyAttributes($propertyReflection);
        $attribute = $this->findAttribute($attributes, OA\Property::class, false);
        if ($attribute && $attribute->getRequired() !== null) {
            return $attribute->getRequired();
        }
        return ! \array_key_exists($propertyReflection->getName(), $objectDefaultValues);
    }
}
