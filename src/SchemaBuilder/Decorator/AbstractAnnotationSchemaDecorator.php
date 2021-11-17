<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation\ValueInterface;
use ScrumWorks\OpenApiSchema\Exception\LogicException;

abstract class AbstractAnnotationSchemaDecorator
{
    private Reader $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    protected function getPropertyAnnotations(ReflectionProperty $propertyReflection): array
    {
        return $this->annotationReader->getPropertyAnnotations($propertyReflection);
    }

    protected function getClassAnnotations(ReflectionClass $classReflection): array
    {
        return $this->annotationReader->getClassAnnotations($classReflection);
    }

    /**
     * @template T as object
     * @param class-string<T> $annotationClass
     * @return T|null
     */
    protected function findAnnotation(
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
                && is_subclass_of($annotation, ValueInterface::class)
            ) {
                throw new LogicException(sprintf("Unexpected annotation '%s'", \get_class($annotation)));
            }
        }

        return $found;
    }
}
