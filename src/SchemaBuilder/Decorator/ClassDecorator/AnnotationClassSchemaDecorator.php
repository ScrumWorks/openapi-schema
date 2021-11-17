<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation\ComponentSchema;
use ScrumWorks\OpenApiSchema\Annotation\Property;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\AbstractAnnotationSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

final class AnnotationClassSchemaDecorator extends AbstractAnnotationSchemaDecorator implements ClassSchemaDecoratorInterface
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

        $classAnnotations = $this->getClassAnnotations($classReflection);

        $componentSchema = $this->findAnnotation($classAnnotations, ComponentSchema::class, true);
        if ($componentSchema instanceof ComponentSchema && $componentSchema->schemaName) {
            $builder = $builder->withSchemaName($componentSchema->schemaName);
        }

        return $builder->withRequiredProperties($requiredProperties);
    }

    private function isPropertyRequired(ReflectionProperty $propertyReflection, array $objectDefaultValues): bool
    {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        $annotation = $this->findAnnotation($annotations, Property::class, false);
        if ($annotation instanceof Property && $annotation->required !== null) {
            return $annotation->required;
        }
        return ! \array_key_exists($propertyReflection->getName(), $objectDefaultValues);
    }
}
