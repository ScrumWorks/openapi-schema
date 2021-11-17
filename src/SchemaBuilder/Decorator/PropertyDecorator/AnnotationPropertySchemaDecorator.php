<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertyDecorator;

use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation\ArrayValue;
use ScrumWorks\OpenApiSchema\Annotation\EnumValue;
use ScrumWorks\OpenApiSchema\Annotation\FloatValue;
use ScrumWorks\OpenApiSchema\Annotation\HashmapValue;
use ScrumWorks\OpenApiSchema\Annotation\IntegerValue;
use ScrumWorks\OpenApiSchema\Annotation\Property;
use ScrumWorks\OpenApiSchema\Annotation\StringValue;
use ScrumWorks\OpenApiSchema\Annotation\Union;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\AbstractAnnotationSchemaDecorator;
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

final class AnnotationPropertySchemaDecorator extends AbstractAnnotationSchemaDecorator implements PropertySchemaDecoratorInterface
{
    public function decoratePropertySchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        $annotations = $this->getPropertyAnnotations($propertyReflection);

        try {
            return $this->decoratePropertySchemaBuilderFromAnnotations($builder, $annotations);
        } catch (LogicException $exception) {
            $propertyIdentification = "{$propertyReflection->getDeclaringClass()->getName()}::{$propertyReflection->getName()}";
            throw new LogicException(
                "Property ${propertyIdentification} annotation problem: " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function decoratePropertySchemaBuilderFromAnnotations(
        AbstractSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $builder = $this->decorateValueSchemaBuilder($builder, $annotations);

        if ($builder instanceof IntegerSchemaBuilder) {
            $builder = $this->decorateIntegerSchemaBuilder($builder, $annotations);
        } elseif ($builder instanceof FloatSchemaBuilder) {
            $builder = $this->decorateFloatSchemaBuilder($builder, $annotations);
        } elseif ($builder instanceof StringSchemaBuilder) {
            $builder = $this->decorateStringSchemaBuilder($builder, $annotations);
        } elseif ($builder instanceof EnumSchemaBuilder) {
            $builder = $this->decorateEnumSchemaBuilder($builder, $annotations);
        } elseif ($builder instanceof ArraySchemaBuilder) {
            $builder = $this->decorateArraySchemaBuilder($builder, $annotations);
        } elseif ($builder instanceof HashmapSchemaBuilder) {
            $builder = $this->decorateHashmapSchemaBuilder($builder, $annotations);
        } elseif ($builder instanceof UnionSchemaBuilder) {
            $builder = $this->decorateUnionSchemaBuilder($builder, $annotations);
        }

        return $builder;
    }

    private function decorateValueSchemaBuilder(
        AbstractSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, Property ::class, false);

        if ($annotation instanceof Property) {
            if ($annotation->description !== null) {
                $builder = $builder->withDescription($annotation->description);
            }

            if ($annotation->nullable !== null) {
                $builder = $builder->withNullable($annotation->nullable);
            }
        }

        return $builder;
    }

    private function decorateIntegerSchemaBuilder(
        IntegerSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, IntegerValue::class);

        if ($annotation instanceof IntegerValue) {
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

    private function decorateFloatSchemaBuilder(
        FloatSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, FloatValue::class);

        if ($annotation instanceof FloatValue) {
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

    private function decorateStringSchemaBuilder(
        StringSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        if ($this->findAnnotation($annotations, EnumValue::class, false)) {
            $builder = (new EnumSchemaBuilder())
                ->withNullable($builder->isNullable());
            return $this->decorateEnumSchemaBuilder($builder, $annotations);
        }

        $annotation = $this->findAnnotation($annotations, StringValue::class);
        if ($annotation instanceof StringValue) {
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

    private function decorateEnumSchemaBuilder(
        EnumSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, EnumValue::class);
        if ($annotation instanceof EnumValue) {
            if ($annotation->enum) {
                $builder = $builder->withEnum($annotation->enum);
            }
        }

        return $builder;
    }

    private function decorateArraySchemaBuilder(
        ArraySchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, ArrayValue::class);

        if ($annotation instanceof ArrayValue) {
            if ($annotation->minItems !== null) {
                $builder = $builder->withMinItems($annotation->minItems);
            }
            if ($annotation->maxItems !== null) {
                $builder = $builder->withMaxItems($annotation->maxItems);
            }
            if ($annotation->uniqueItems !== null) {
                $builder = $builder->withUniqueItems($annotation->uniqueItems);
            }

            $itemsBuilder = $builder->getItemsSchemaBuilder();
            if ($annotation->itemsSchema !== null && $itemsBuilder) {
                $itemsBuilder = $this->decoratePropertySchemaBuilderFromAnnotations(
                    $itemsBuilder,
                    [$annotation->itemsSchema]
                );
                $builder = $builder->withItemsSchemaBuilder($itemsBuilder);
            }
        }

        return $builder;
    }

    private function decorateHashmapSchemaBuilder(
        HashmapSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, HashmapValue::class);

        if ($annotation instanceof HashmapValue) {
            if ($annotation->requiredProperties !== null) {
                $builder = $builder->withRequiredProperties($annotation->requiredProperties);
            }

            $itemsBuilder = $builder->getItemsSchemaBuilder();

            if ($annotation->itemsSchema !== null && $itemsBuilder) {
                $itemsBuilder = $this->decoratePropertySchemaBuilderFromAnnotations(
                    $itemsBuilder,
                    [$annotation->itemsSchema]
                );
                $builder = $builder->withItemsSchemaBuilder($itemsBuilder);
            }
        }

        return $builder;
    }

    private function decorateUnionSchemaBuilder(
        UnionSchemaBuilder $builder,
        array $annotations
    ): AbstractSchemaBuilder {
        $annotation = $this->findAnnotation($annotations, Union::class);

        if ($annotation instanceof Union) {
            $possibleSchemaBuilders = $builder->getPossibleSchemaBuilders();
            $annotatedBuilders = [];
            $typeAnnotations = $annotation->types ?? [];
            while (
                ($typeAnnotation = array_shift($typeAnnotations))
                && ($possibleSchemaBuilder = array_shift($possibleSchemaBuilders))
            ) {
                $annotatedBuilders[] = $this->decoratePropertySchemaBuilderFromAnnotations(
                    $possibleSchemaBuilder,
                    [$typeAnnotation]
                );
            }
            $possibleSchemaBuilders = array_merge($annotatedBuilders, $possibleSchemaBuilders);

            $discriminator = $annotation->discriminator;
            if ($discriminator) {
                foreach ($possibleSchemaBuilders as $possibleSchemaBuilder) {
                    if (
                        ! $possibleSchemaBuilder instanceof ObjectSchemaBuilder
                        || ! isset($possibleSchemaBuilder->getPropertiesSchemaBuilders()[$discriminator])
                    ) {
                        throw new LogicException(
                            "All types have to be objects and contain discriminator property '${discriminator}'."
                        );
                    }
                }
                $builder = $builder->withDiscriminatorPropertyName($discriminator);
            }

            if ($annotation->mapping !== null) {
                $possibleSchemaBuildersMap = [];
                foreach ($possibleSchemaBuilders as $possibleSchemaBuilder) {
                    $schemaName = $possibleSchemaBuilder->getSchemaName();
                    if ($schemaName) {
                        $possibleSchemaBuildersMap[$schemaName] = $possibleSchemaBuilder;
                    } else {
                        throw new LogicException(
                            'All union types have to have schema name if there is mapping specified.'
                        );
                    }
                }

                $mappedBuilders = [];
                foreach ($annotation->mapping as $k => $v) {
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
