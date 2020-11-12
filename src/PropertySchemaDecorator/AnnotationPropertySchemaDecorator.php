<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\PropertySchemaDecorator;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\BooleanSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\EnumSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\FloatSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\IntegerSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\MixedSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;

class AnnotationPropertySchemaDecorator implements PropertySchemaDecoratorInterface
{
    private Reader $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function decorateValueSchemaBuilder(
        AbstractSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($annotation = $this->findAnnotation($annotations, OA\Property::class, false)) {
            /** @var OA\Property $annotation */
            if ($annotation->description !== null) {
                $builder = $builder->withDescription($annotation->description);
            }
        }

        return $builder;
    }

    public function decorateMixedSchemaBuilder(
        MixedSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        return $builder;
    }

    public function decorateIntegerSchemaBuilder(
        IntegerSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($annotation = $this->findAnnotation($annotations, OA\IntegerValue::class)) {
            /** @var OA\IntegerValue $annotation */
            if ($annotation->minimum !== null) {
                $builder = $builder->withMinimum($annotation->minimum);
            }
            if ($annotation->maximum !== null) {
                $builder = $builder->withMaximum($annotation->maximum);
            }
            if ($annotation->exclusiveMinimum !== null) {
                $builder = $builder->withExclusiveMinimum($annotation->exclusiveMinimum);
            }
            if ($annotation->exclusiveMaximum !== null) {
                $builder = $builder->withExclusiveMaximum($annotation->exclusiveMaximum);
            }
            if ($annotation->multipleOf !== null) {
                $builder = $builder->withMultipleOf($annotation->multipleOf);
            }
        }

        return $builder;
    }

    public function decorateFloatSchemaBuilder(
        FloatSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($annotation = $this->findAnnotation($annotations, OA\FloatValue::class)) {
            /** @var OA\FloatValue $annotation */
            if ($annotation->minimum !== null) {
                $builder = $builder->withMinimum($annotation->minimum);
            }
            if ($annotation->maximum !== null) {
                $builder = $builder->withMaximum($annotation->maximum);
            }
            if ($annotation->exclusiveMinimum !== null) {
                $builder = $builder->withExclusiveMinimum($annotation->exclusiveMinimum);
            }
            if ($annotation->exclusiveMaximum !== null) {
                $builder = $builder->withExclusiveMaximum($annotation->exclusiveMaximum);
            }
            if ($annotation->multipleOf !== null) {
                $builder = $builder->withMultipleOf($annotation->multipleOf);
            }
        }

        return $builder;
    }

    public function decorateBooleanSchemaBuilder(
        BooleanSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        return $builder;
    }

    public function decorateStringSchemaBuilder(
        StringSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($this->findAnnotation($annotations, OA\EnumValue::class, false)) {
            $builder = new EnumSchemaBuilder();
            return $this->decorateEnumSchemaBuilder($builder, $propertyReflection);
        }

        if ($annotation = $this->findAnnotation($annotations, OA\StringValue::class)) {
            /** @var OA\StringValue $annotation */
            if ($annotation->minLength !== null) {
                $builder = $builder->withMinLength($annotation->minLength);
            }
            if ($annotation->maxLength !== null) {
                $builder = $builder->withMaxLength($annotation->maxLength);
            }
            if ($annotation->format !== null) {
                $builder = $builder->withFormat($annotation->format);
            }
            if ($annotation->pattern !== null) {
                $builder = $builder->withPattern($annotation->pattern);
            }
        }

        return $builder;
    }

    public function decorateEnumSchemaBuilder(
        EnumSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($annotation = $this->findAnnotation($annotations, OA\EnumValue::class)) {
            /** @var OA\EnumValue $annotation */
            if ($annotation->enum) {
                $builder = $builder->withEnum($annotation->enum);
            }
        }

        return $builder;
    }

    public function decorateArraySchemaBuilder(
        ArraySchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($annotation = $this->findAnnotation($annotations, OA\ArrayValue::class)) {
            /** @var OA\ArrayValue $annotation */
            if ($annotation->minItems !== null) {
                $builder = $builder->withMinItems($annotation->minItems);
            }
            if ($annotation->maxItems !== null) {
                $builder = $builder->withMaxItems($annotation->maxItems);
            }
            if ($annotation->uniqueItems !== null) {
                $builder = $builder->withUniqueItems($annotation->uniqueItems);
            }
        }

        return $builder;
    }

    public function decorateHashmapSchemaBuilder(
        HashmapSchemaBuilder $builder,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        if ($annotation = $this->findAnnotation($annotations, OA\HashmapValue::class)) {
            /** @var OA\HashmapValue $annotation */
            if ($annotation->requiredProperties !== null) {
                $builder = $builder->withRequiredProperties($annotation->requiredProperties);
            }
        }

        return $builder;
    }

    public function decorateObjectSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflexion,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $objectDefaultValues = $classReflexion->getDefaultProperties();
        $requiredProperties = [];
        foreach (\array_keys($builder->getPropertiesSchemas()) as $propertyName) {
            $propertyReflection = $classReflexion->getProperty($propertyName);
            if ($this->isPropertyRequired($propertyReflection, $objectDefaultValues)) {
                $requiredProperties[] = $propertyName;
            }
        }
        return $builder->withRequiredProperties($requiredProperties);
    }

    private function isPropertyRequired(?ReflectionProperty $propertyReflection, array $objectDefaultValues): bool
    {
        $annotations = $this->getPropertyAnnotations($propertyReflection);
        /** @var ?OA\Property $annotation */
        $annotation = $this->findAnnotation($annotations, OA\Property::class, false);
        if ($annotation && $annotation->required !== null) {
            return $annotation->required;
        }
        return ! \array_key_exists($propertyReflection->getName(), $objectDefaultValues);
    }

    private function getPropertyAnnotations(?ReflectionProperty $propertyReflection): array
    {
        if ($propertyReflection === null) {
            return [];
        }
        return (array) $this->annotationReader->getPropertyAnnotations($propertyReflection);
    }

    private function findAnnotation(
        array $annotations,
        string $annotationClass,
        bool $exceptionOnAnotherValueInterface = true
    ): ?object {
        $found = null;
        foreach ($annotations as $annotation) {
            if (\get_class($annotation) === $annotationClass) {
                // micro-optimalization
                if (! $exceptionOnAnotherValueInterface) {
                    return $annotation;
                }

                $found = $annotation;
            } elseif (
                $exceptionOnAnotherValueInterface
                && \is_subclass_of($annotation, OA\ValueInterface::class)
            ) {
                throw new LogicException(\sprintf("Unexpected annotation '%s'", \get_class($annotation)));
            }
        }

        return $found;
    }
}
