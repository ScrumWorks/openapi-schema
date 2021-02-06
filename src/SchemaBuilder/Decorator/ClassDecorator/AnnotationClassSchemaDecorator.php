<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\AbstractAnnotationSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

final class AnnotationClassSchemaDecorator extends AbstractAnnotationSchemaDecorator implements ClassSchemaDecoratorInterface
{
    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): ObjectSchemaBuilder {
        $objectDefaultValues = $classReflection->getDefaultProperties();
        $requiredProperties = [];
        foreach ($classReflection->getProperties() as $propertyReflection) {
            if ($this->isPropertyRequired($propertyReflection, $objectDefaultValues)) {
                $requiredProperties[] = $propertyReflection->getName();
            }
        }

        $classAnnotations = $this->getClassAnnotations($classReflection);
        /** @var ?OA\ObjectValue $objectAnnotation */
        $objectAnnotation = $this->findAnnotation($classAnnotations, OA\ObjectValue::class, true);
        if ($objectAnnotation && $objectAnnotation->schemaName) {
            $builder = $builder->withSchemaName($objectAnnotation->schemaName);
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
