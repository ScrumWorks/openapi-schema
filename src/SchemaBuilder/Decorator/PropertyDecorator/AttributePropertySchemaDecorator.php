<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertyDecorator;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Attribute as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\AbstractAttributeSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertySchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\EnumSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\FloatSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\IntegerSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\UnionSchemaBuilder;

final class AttributePropertySchemaDecorator extends AbstractAttributeSchemaDecorator implements PropertySchemaDecoratorInterface
{
    public function decoratePropertySchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $attrs = $this->getPropertyAttributes($propertyReflection);

        try {
            return $this->decoratePropertySchemaBuilderFromAttributes($builder, $attrs);
        } catch (LogicException $exception) {
            $propertyIdentification = "{$propertyReflection->getDeclaringClass()->getName()}::{$propertyReflection->getName()}";
            throw new LogicException(
                "Property {$propertyIdentification} attribute problem: " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param object[] $attributes
     */
    public function decoratePropertySchemaBuilderFromAttributes(
        AbstractSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        $builder = $this->decorateValueSchemaBuilder($builder, $attributes);

        if ($builder instanceof IntegerSchemaBuilder) {
            $builder = $this->decorateIntegerSchemaBuilder($builder, $attributes);
        } elseif ($builder instanceof FloatSchemaBuilder) {
            $builder = $this->decorateFloatSchemaBuilder($builder, $attributes);
        } elseif ($builder instanceof StringSchemaBuilder) {
            $builder = $this->decorateStringSchemaBuilder($builder, $attributes);
        } elseif ($builder instanceof EnumSchemaBuilder) {
            $builder = $this->decorateEnumSchemaBuilder($builder, $attributes);
        } elseif ($builder instanceof ArraySchemaBuilder) {
            $builder = $this->decorateArraySchemaBuilder($builder, $attributes);
        } elseif ($builder instanceof HashmapSchemaBuilder) {
            $builder = $this->decorateHashmapSchemaBuilder($builder, $attributes);
        } elseif ($builder instanceof UnionSchemaBuilder) {
            $builder = $this->decorateUnionSchemaBuilder($builder, $attributes);
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateValueSchemaBuilder(
        AbstractSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\Property ::class, false)) {
            if ($attribute->getDescription() !== null) {
                $builder = $builder->withDescription($attribute->getDescription());
            }

            if ($attribute->getNullable() !== null) {
                $builder = $builder->withNullable($attribute->getNullable());
            }

            if ($attribute->getDeprecated() !== null) {
                $builder = $builder->withDeprecated($attribute->getDeprecated());
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateIntegerSchemaBuilder(
        IntegerSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\IntegerValue::class)) {
            if ($attribute->getMinimum() !== null) {
                $builder = $builder->withMinimum($attribute->getMinimum());
            }
            if ($attribute->getMaximum() !== null) {
                $builder = $builder->withMaximum($attribute->getMaximum());
            }
            if ($attribute->getExclusiveMinimum() !== null) {
                $builder = $builder->withExclusiveMinimum($attribute->getExclusiveMinimum());
            }
            if ($attribute->getExclusiveMaximum() !== null) {
                $builder = $builder->withExclusiveMaximum($attribute->getExclusiveMaximum());
            }
            if ($attribute->getMultipleOf() !== null) {
                $builder = $builder->withMultipleOf($attribute->getMultipleOf());
            }
            if ($attribute->getExample() !== null) {
                $builder = $builder->withExample($attribute->getExample());
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateFloatSchemaBuilder(
        FloatSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\FloatValue::class)) {
            if ($attribute->getMinimum() !== null) {
                $builder = $builder->withMinimum($attribute->getMinimum());
            }
            if ($attribute->getMaximum() !== null) {
                $builder = $builder->withMaximum($attribute->getMaximum());
            }
            if ($attribute->getExclusiveMinimum() !== null) {
                $builder = $builder->withExclusiveMinimum($attribute->getExclusiveMinimum());
            }
            if ($attribute->getExclusiveMaximum() !== null) {
                $builder = $builder->withExclusiveMaximum($attribute->getExclusiveMaximum());
            }
            if ($attribute->getMultipleOf() !== null) {
                $builder = $builder->withMultipleOf($attribute->getMultipleOf());
            }
            if ($attribute->getExample() !== null) {
                $builder = $builder->withExample($attribute->getExample());
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateStringSchemaBuilder(
        StringSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($this->findAttribute($attributes, OA\EnumValue::class, false)) {
            $builder = (new EnumSchemaBuilder())
                ->withNullable($builder->isNullable());
            return $this->decorateEnumSchemaBuilder($builder, $attributes);
        }

        if ($attribute = $this->findAttribute($attributes, OA\StringValue::class)) {
            if ($attribute->getMinLength() !== null) {
                $builder = $builder->withMinLength($attribute->getMinLength());
            }
            if ($attribute->getMaxLength() !== null) {
                $builder = $builder->withMaxLength($attribute->getMaxLength());
            }
            if ($attribute->getFormat() !== null) {
                $builder = $builder->withFormat($attribute->getFormat());
            }
            if ($attribute->getPattern() !== null) {
                $builder = $builder->withPattern($attribute->getPattern());
            }
            if ($attribute->getExample() !== null) {
                $builder = $builder->withExample($attribute->getExample());
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateEnumSchemaBuilder(
        EnumSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\EnumValue::class)) {
            if ($attribute->getEnum()) {
                $builder = $builder->withEnum($attribute->getEnum());
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateArraySchemaBuilder(
        ArraySchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\ArrayValue::class)) {
            if ($attribute->getMinItems() !== null) {
                $builder = $builder->withMinItems($attribute->getMinItems());
            }
            if ($attribute->getMaxItems() !== null) {
                $builder = $builder->withMaxItems($attribute->getMaxItems());
            }
            if ($attribute->getUniqueItems() !== null) {
                $builder = $builder->withUniqueItems($attribute->getUniqueItems());
            }
            if ($attribute->getItemsSchema() !== null && ($itemsBuilder = $builder->getItemsSchemaBuilder())) {
                $itemsBuilder = $this->decoratePropertySchemaBuilderFromAttributes(
                    $itemsBuilder,
                    [$attribute->getItemsSchema()]
                );
                $builder = $builder->withItemsSchemaBuilder($itemsBuilder);
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateHashmapSchemaBuilder(
        HashmapSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\HashmapValue::class)) {
            if ($attribute->getRequiredProperties() !== null) {
                $builder = $builder->withRequiredProperties($attribute->getRequiredProperties());
            }
            if ($attribute->getItemsSchema() !== null && ($itemsBuilder = $builder->getItemsSchemaBuilder())) {
                $itemsBuilder = $this->decoratePropertySchemaBuilderFromAttributes(
                    $itemsBuilder,
                    [$attribute->getItemsSchema()]
                );
                $builder = $builder->withItemsSchemaBuilder($itemsBuilder);
            }
        }

        return $builder;
    }

    /**
     * @param object[] $attributes
     */
    private function decorateUnionSchemaBuilder(
        UnionSchemaBuilder $builder,
        array $attributes,
    ): AbstractSchemaBuilder {
        if ($attribute = $this->findAttribute($attributes, OA\Union::class)) {
            $possibleSchemaBuilders = $builder->getPossibleSchemaBuilders();
            $attributedBuilders = [];
            $typeAttributes = $attribute->getTypes() ?? [];
            while (
                ($typeAttribute = \array_shift($typeAttributes))
                && ($possibleSchemaBuilder = \array_shift($possibleSchemaBuilders))
            ) {
                $attributedBuilders[] = $this->decoratePropertySchemaBuilderFromAttributes(
                    $possibleSchemaBuilder,
                    [$typeAttribute]
                );
            }
            $possibleSchemaBuilders = \array_merge($attributedBuilders, $possibleSchemaBuilders);

            if ($discriminator = $attribute->getDiscriminator()) {
                foreach ($possibleSchemaBuilders as $possibleSchemaBuilder) {
                    if (
                        ! $possibleSchemaBuilder instanceof ObjectSchemaBuilder
                        || ! isset($possibleSchemaBuilder->getPropertiesSchemaBuilders()[$discriminator])
                    ) {
                        throw new LogicException(
                            "All types have to be objects and contain discriminator property '{$discriminator}'."
                        );
                    }
                }
                $builder = $builder->withDiscriminatorPropertyName($discriminator);
            }

            if ($attribute->getMapping() !== null) {
                $possibleSchemaBuildersMap = [];
                foreach ($possibleSchemaBuilders as $possibleSchemaBuilder) {
                    if ($schemaName = $possibleSchemaBuilder->getSchemaName()) {
                        $possibleSchemaBuildersMap[$schemaName] = $possibleSchemaBuilder;
                    } else {
                        throw new LogicException(
                            'All union types have to have schema name if there is mapping specified.'
                        );
                    }
                }

                $mappedBuilders = [];
                foreach ($attribute->getMapping() as $k => $v) {
                    if (isset($possibleSchemaBuildersMap[$v])) {
                        $mappedBuilders[$k] = $possibleSchemaBuildersMap[$v];
                    } else {
                        throw new LogicException("Unknown schema with name '{$v}' in discriminator mapping.");
                    }
                }
                $possibleSchemaBuilders = $mappedBuilders;
            }

            $builder = $builder->withPossibleSchemaBuilders($possibleSchemaBuilders);
        }

        return $builder;
    }
}
