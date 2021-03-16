<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation as OA;
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
        /** @var ?OA\ComponentSchema $componentSchema */
        $componentSchema = $this->findAnnotation($classAnnotations, OA\ComponentSchema::class, true);
        if ($componentSchema && $componentSchema->schemaName) {
            $builder = $builder->withSchemaName($componentSchema->schemaName);
        }

        return $builder->withRequiredProperties($requiredProperties);
    }

    private function isPropertyRequired(ReflectionProperty $propertyReflection, array $objectDefaultValues): bool
    {
        $annotations = $this->getPropertyAnnotations($propertyReflection);
        /** @var ?OA\Property $annotation */
        $annotation = $this->findAnnotation($annotations, OA\Property::class, false);
        if ($annotation && $annotation->required !== null) {
            return $annotation->required;
        }
        return ! \array_key_exists($propertyReflection->getName(), $objectDefaultValues);
    }
}
