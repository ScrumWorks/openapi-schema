<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use Doctrine\Common\Annotations\Reader;
use Exception;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\Annotation as OA;
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
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\PropertyTypeReaderInterface;
use ScrumWorks\PropertyReader\VariableType\ArrayVariableType;
use ScrumWorks\PropertyReader\VariableType\ClassVariableType;
use ScrumWorks\PropertyReader\VariableType\MixedVariableType;
use ScrumWorks\PropertyReader\VariableType\ScalarVariableType;
use ScrumWorks\PropertyReader\VariableType\UnionVariableType;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;

final class SchemaParser implements SchemaParserInterface
{
    private PropertyTypeReaderInterface $propertyReader;

    private Reader $annotationReader;

    public function __construct(PropertyTypeReaderInterface $propertyReader, Reader $annotationReader)
    {
        $this->propertyReader = $propertyReader;
        $this->annotationReader = $annotationReader;
    }

    public function getEntitySchema(string $class): ObjectSchema
    {
        $builder = $this->createClassSchemaBuilder($class, null);
        return $builder->build();
    }

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface
    {
        $variableType = $this->propertyReader->readUnifiedVariableType($propertyReflection);
        return $this->variableTypeToVariableSchema($variableType, $propertyReflection);
    }

    public function getVariableTypeSchema(?VariableTypeInterface $variableType): ValueSchemaInterface
    {
        return $this->variableTypeToVariableSchema($variableType, null);
    }

    private function variableTypeToVariableSchema(
        ?VariableTypeInterface $variableType,
        ?ReflectionProperty $propertyReflection
    ): ValueSchemaInterface {
        if ($variableType === null) {
            $schemaBuilder = $this->createMixedSchemaBuilder($propertyReflection);
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = $this->createMixedSchemaBuilder($propertyReflection);
        } elseif ($variableType instanceof ScalarVariableType) {
            switch ($variableType->getType()) {
                case ScalarVariableType::TYPE_INTEGER:
                    $schemaBuilder = $this->createIntegerSchemaBuilder($propertyReflection);
                    break;
                case ScalarVariableType::TYPE_FLOAT:
                    $schemaBuilder = $this->createFloatSchemaBuilder($propertyReflection);
                    break;
                case ScalarVariableType::TYPE_BOOLEAN:
                    $schemaBuilder = $this->createBooleanSchemaBuilder($propertyReflection);
                    break;
                case ScalarVariableType::TYPE_STRING:
                    $schemaBuilder = $this->createStringSchemaBuilder($propertyReflection);
                    break;
            }
        } elseif ($variableType instanceof ArrayVariableType) {
            if ($variableType->getKeyType() === null) {
                $schemaBuilder = $this->createArraySchemaBuilder($propertyReflection);
            } else {
                $schemaBuilder = $this->createHashmapSchemaBuilder($propertyReflection);
            }
            $schemaBuilder = $schemaBuilder->withItemsSchema(
                $this->variableTypeToVariableSchema($variableType->getItemType(), null)
            );
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createClassSchemaBuilder($variableType->getClass(), null);
        } elseif ($variableType instanceof UnionVariableType) {
            $schemaBuilder = $this->createUnionSchemaBuilder($variableType, $propertyReflection);
        }
        if (! isset($schemaBuilder)) {
            throw new Exception('TODO');
        }

        if ($schemaBuilder instanceof MixedSchemaBuilder) {
            $schemaBuilder = $schemaBuilder->withNullable(true);
        } else {
            $schemaBuilder = $schemaBuilder->withNullable($variableType->isNullable());
        }

        if ($propertyReflection) {
            /** @var ?OA\Property $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\Property::class);
            if ($annotation) {
                if ($annotation->description !== null) {
                    $schemaBuilder = $schemaBuilder->withDescription($annotation->description);
                }
            }
        }

        return $schemaBuilder->build();
    }

    private function createMixedSchemaBuilder(?ReflectionProperty $propertyReflection): MixedSchemaBuilder
    {
        return new MixedSchemaBuilder();
    }

    private function createIntegerSchemaBuilder(?ReflectionProperty $propertyReflection): IntegerSchemaBuilder
    {
        $schemaBuilder = new IntegerSchemaBuilder();

        if ($propertyReflection) {
            /** @var ?OA\IntegerValue $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\IntegerValue::class);
            if ($annotation) {
                if ($annotation->minimum !== null) {
                    $schemaBuilder = $schemaBuilder->withMinimum($annotation->minimum);
                }
                if ($annotation->maximum !== null) {
                    $schemaBuilder = $schemaBuilder->withMaximum($annotation->maximum);
                }
                if ($annotation->exclusiveMinimum !== null) {
                    $schemaBuilder = $schemaBuilder->withExclusiveMinimum($annotation->exclusiveMinimum);
                }
                if ($annotation->exclusiveMaximum !== null) {
                    $schemaBuilder = $schemaBuilder->withExclusiveMaximum($annotation->exclusiveMaximum);
                }
                if ($annotation->multipleOf !== null) {
                    $schemaBuilder = $schemaBuilder->withMultipleOf($annotation->multipleOf);
                }
            }
        }

        return $schemaBuilder;
    }

    private function createFloatSchemaBuilder(?ReflectionProperty $propertyReflection): FloatSchemaBuilder
    {
        $schemaBuilder = new FloatSchemaBuilder();

        if ($propertyReflection) {
            /** @var ?OA\FloatValue $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\FloatValue::class);
            if ($annotation) {
                if ($annotation->minimum !== null) {
                    $schemaBuilder = $schemaBuilder->withMinimum($annotation->minimum);
                }
                if ($annotation->maximum !== null) {
                    $schemaBuilder = $schemaBuilder->withMaximum($annotation->maximum);
                }
                if ($annotation->exclusiveMinimum !== null) {
                    $schemaBuilder = $schemaBuilder->withExclusiveMinimum($annotation->exclusiveMinimum);
                }
                if ($annotation->exclusiveMaximum !== null) {
                    $schemaBuilder = $schemaBuilder->withExclusiveMaximum($annotation->exclusiveMaximum);
                }
                if ($annotation->multipleOf !== null) {
                    $schemaBuilder = $schemaBuilder->withMultipleOf($annotation->multipleOf);
                }
            }
        }

        return $schemaBuilder;
    }

    private function createBooleanSchemaBuilder(?ReflectionProperty $propertyReflection): BooleanSchemaBuilder
    {
        return new BooleanSchemaBuilder();
    }

    private function createStringSchemaBuilder(?ReflectionProperty $propertyReflection): AbstractSchemaBuilder
    {
        $schemaBuilder = new StringSchemaBuilder();

        if ($propertyReflection) {
            /** @var ?OA\EnumValue $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\EnumValue::class);
            if ($annotation) {
                $schemaBuilder = new EnumSchemaBuilder();
                if ($annotation->enum) {
                    $schemaBuilder = $schemaBuilder->withEnum($annotation->enum);
                }
                return $schemaBuilder;
            }

            /** @var ?OA\StringValue $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\StringValue::class);
            if ($annotation) {
                if ($annotation->minLength !== null) {
                    $schemaBuilder = $schemaBuilder->withMinLength($annotation->minLength);
                }
                if ($annotation->maxLength !== null) {
                    $schemaBuilder = $schemaBuilder->withMaxLength($annotation->maxLength);
                }
                if ($annotation->format !== null) {
                    $schemaBuilder = $schemaBuilder->withFormat($annotation->format);
                }
                if ($annotation->pattern !== null) {
                    $schemaBuilder = $schemaBuilder->withPattern($annotation->pattern);
                }
            }
        }

        return $schemaBuilder;
    }

    private function createArraySchemaBuilder(?ReflectionProperty $propertyReflection): ArraySchemaBuilder
    {
        $schemaBuilder = new ArraySchemaBuilder();

        if ($propertyReflection) {
            /** @var ?OA\ArrayValue $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\ArrayValue::class);
            if ($annotation) {
                if ($annotation->minItems !== null) {
                    $schemaBuilder = $schemaBuilder->withMinItems($annotation->minItems);
                }
                if ($annotation->maxItems !== null) {
                    $schemaBuilder = $schemaBuilder->withMaxItems($annotation->maxItems);
                }
                if ($annotation->uniqueItems !== null) {
                    $schemaBuilder = $schemaBuilder->withUniqueItems($annotation->uniqueItems);
                }
            }
        }

        return $schemaBuilder;
    }

    private function createHashmapSchemaBuilder(?ReflectionProperty $propertyReflection): HashmapSchemaBuilder
    {
        $schemaBuilder = new HashmapSchemaBuilder();

        if ($propertyReflection) {
            /** @var ?OA\HashmapValue $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, OA\HashmapValue::class);
            if ($annotation) {
                if ($annotation->requiredProperties !== null) {
                    $schemaBuilder = $schemaBuilder->withRequiredProperties($annotation->requiredProperties);
                }
            }
        }

        return $schemaBuilder;
    }

    private function createClassSchemaBuilder(
        string $class,
        ?ReflectionProperty $innerPropertyReflection
    ): ObjectSchemaBuilder {
        if (! \class_exists($class)) {
            throw new Exception('TODO');
        }

        $reflection = new ReflectionClass($class);

        $propertiesSchemas = [];
        foreach ($reflection->getProperties() as $propertyReflection) {
            // if property is not public, then skip it.
            if (! $propertyReflection->isPublic()) {
                continue;
            }

            $propertiesSchemas[$propertyReflection->getName()] = $this->getPropertySchema($propertyReflection);
        }
        return (new ObjectSchemaBuilder())->withPropertiesSchemas($propertiesSchemas);
    }

    private function createUnionSchemaBuilder(
        UnionVariableType $unionVariableType,
        ?ReflectionProperty $propertyReflection
    ): AbstractSchemaBuilder {
        throw new Exception('Union types are not supported');
    }
}
