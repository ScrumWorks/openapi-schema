<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Attribute\ValueInterface;
use ScrumWorks\OpenApiSchema\Exception\LogicException;

abstract class AbstractAttributeSchemaDecorator
{
    /**
     * @return object[]
     */
    protected function getPropertyAttributes(ReflectionProperty $propertyReflection): array
    {
        return $this->instantiateAttributes($propertyReflection->getAttributes());
    }

    /**
     * @return object[]
     */
    protected function getClassAttributes(ReflectionClass $classReflection): array
    {
        return $this->instantiateAttributes($classReflection->getAttributes());
    }

    /**
     * @template T of object
     * @param object[] $attributes
     * @param class-string<T> $attributeClass
     * @return T|null
     */
    protected function findAttribute(
        array $attributes,
        string $attributeClass,
        bool $exceptionOnAnotherValueInterface = true,
    ): ?object {
        $found = null;
        foreach ($attributes as $attribute) {
            if (\get_class($attribute) === $attributeClass) {
                // micro-optimalization
                if (! $exceptionOnAnotherValueInterface) {
                    return $attribute;
                }

                $found = $attribute;
            } elseif (
                $exceptionOnAnotherValueInterface
                && \is_subclass_of($attribute, ValueInterface::class)
            ) {
                throw new LogicException(\sprintf("Unexpected attribute '%s'", $attribute::class));
            }
        }

        return $found;
    }

    /**
     * @param ReflectionAttribute[] $attributeReflections
     * @return object[]
     */
    private function instantiateAttributes(array $attributeReflections): array
    {
        return \array_map(
            static fn (ReflectionAttribute $reflectionAttribute): object => $reflectionAttribute->newInstance(),
            $attributeReflections,
        );
    }
}
