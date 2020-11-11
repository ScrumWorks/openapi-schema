<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use Doctrine\Common\Annotations\Reader;
use Exception;
use LogicException;
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
        $builder = $this->createClassSchemaBuilder($class, []);
        return $builder->build();
    }

    public function getPropertySchema(ReflectionProperty $propertyReflection): ValueSchemaInterface
    {
        $variableType = $this->propertyReader->readUnifiedVariableType($propertyReflection);
        $annotations = (array) $this->annotationReader->getPropertyAnnotations($propertyReflection);
        return $this->variableTypeToVariableSchema($variableType, $annotations);
    }

    public function getVariableTypeSchema(?VariableTypeInterface $variableType): ValueSchemaInterface
    {
        return $this->variableTypeToVariableSchema($variableType, []);
    }

    private function variableTypeToVariableSchema(
        ?VariableTypeInterface $variableType,
        array $annotations
    ): ValueSchemaInterface {
        if ($variableType === null) {
            $schemaBuilder = $this->createMixedSchemaBuilder($annotations);
        } elseif ($variableType instanceof MixedVariableType) {
            $schemaBuilder = $this->createMixedSchemaBuilder($annotations);
        } elseif ($variableType instanceof ScalarVariableType) {
            switch ($variableType->getType()) {
                case ScalarVariableType::TYPE_INTEGER:
                    $schemaBuilder = $this->createIntegerSchemaBuilder($annotations);
                    break;
                case ScalarVariableType::TYPE_FLOAT:
                    $schemaBuilder = $this->createFloatSchemaBuilder($annotations);
                    break;
                case ScalarVariableType::TYPE_BOOLEAN:
                    $schemaBuilder = $this->createBooleanSchemaBuilder($annotations);
                    break;
                case ScalarVariableType::TYPE_STRING:
                    $schemaBuilder = $this->createStringSchemaBuilder($annotations);
                    break;
            }
        } elseif ($variableType instanceof ArrayVariableType) {
            if ($variableType->getKeyType() === null) {
                $schemaBuilder = $this->createArraySchemaBuilder($annotations);
            } else {
                $schemaBuilder = $this->createHashmapSchemaBuilder($annotations);
            }
            $schemaBuilder = $schemaBuilder->withItemsSchema(
                $this->variableTypeToVariableSchema($variableType->getItemType(), [])
            );
        } elseif ($variableType instanceof ClassVariableType) {
            $schemaBuilder = $this->createClassSchemaBuilder($variableType->getClass(), []);
        } elseif ($variableType instanceof UnionVariableType) {
            $schemaBuilder = $this->createUnionSchemaBuilder($variableType, $annotations);
        }
        if (! isset($schemaBuilder)) {
            throw new Exception('TODO');
        }

        if ($schemaBuilder instanceof MixedSchemaBuilder) {
            $schemaBuilder = $schemaBuilder->withNullable(true);
        } else {
            $schemaBuilder = $schemaBuilder->withNullable($variableType->isNullable());
        }

        if ($annotation = $this->findAnnotation($annotations, OA\Property::class, false)) {
            /** @var ?OA\Property $annotation */
            if ($annotation->description !== null) {
                $schemaBuilder = $schemaBuilder->withDescription($annotation->description);
            }
        }

        return $schemaBuilder->build();
    }

    private function createMixedSchemaBuilder(array $annotations): MixedSchemaBuilder
    {
        return new MixedSchemaBuilder();
    }

    private function createIntegerSchemaBuilder(array $annotations): IntegerSchemaBuilder
    {
        $schemaBuilder = new IntegerSchemaBuilder();

        if ($annotation = $this->findAnnotation($annotations, OA\IntegerValue::class)) {
            /** @var ?OA\IntegerValue $annotation */
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

        return $schemaBuilder;
    }

    private function createFloatSchemaBuilder(array $annotations): FloatSchemaBuilder
    {
        $schemaBuilder = new FloatSchemaBuilder();

        if ($annotation = $this->findAnnotation($annotations, OA\FloatValue::class)) {
            /** @var ?OA\FloatValue $annotation */
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

        return $schemaBuilder;
    }

    private function createBooleanSchemaBuilder(array $annotations): BooleanSchemaBuilder
    {
        return new BooleanSchemaBuilder();
    }

    private function createStringSchemaBuilder(array $annotations): AbstractSchemaBuilder
    {
        // try is its enum
        if ($annotation = $this->findAnnotation($annotations, OA\EnumValue::class, false)) {
            // ensure there is only EnumValue annotation, not StringValue
            $this->findAnnotation($annotations, OA\EnumValue::class);

            $schemaBuilder = new EnumSchemaBuilder();
            /** @var ?OA\EnumValue $annotation */
            if ($annotation->enum) {
                $schemaBuilder = $schemaBuilder->withEnum($annotation->enum);
            }
            return $schemaBuilder;
        }

        $schemaBuilder = new StringSchemaBuilder();
        if ($annotation = $this->findAnnotation($annotations, OA\StringValue::class)) {
            /** @var ?OA\StringValue $annotation */
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

        return $schemaBuilder;
    }

    private function createArraySchemaBuilder(array $annotations): ArraySchemaBuilder
    {
        $schemaBuilder = new ArraySchemaBuilder();

        if ($annotation = $this->findAnnotation($annotations, OA\ArrayValue::class)) {
            /** @var ?OA\ArrayValue $annotation */
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

        return $schemaBuilder;
    }

    private function createHashmapSchemaBuilder(array $annotations): HashmapSchemaBuilder
    {
        $schemaBuilder = new HashmapSchemaBuilder();

        if ($annotation = $this->findAnnotation($annotations, OA\HashmapValue::class)) {
            /** @var ?OA\HashmapValue $annotation */
            if ($annotation->requiredProperties !== null) {
                $schemaBuilder = $schemaBuilder->withRequiredProperties($annotation->requiredProperties);
            }
        }

        return $schemaBuilder;
    }

    private function createClassSchemaBuilder(string $class, array $annotations): ObjectSchemaBuilder
    {
        if (! \class_exists($class)) {
            throw new Exception('TODO');
        }

        // TODO maybe also read annotations from class iteself

        $reflection = new ReflectionClass($class);
        $objectDefaultValues = $reflection->getDefaultProperties();

        $propertiesSchemas = [];
        $requiredProperties = [];
        foreach ($reflection->getProperties() as $propertyReflection) {
            // if property is not public, then skip it.
            if (! $propertyReflection->isPublic()) {
                continue;
            }

            $propertyName = $propertyReflection->getName();
            if ($this->isPropertyRequired($propertyReflection, $objectDefaultValues)) {
                $requiredProperties[] = $propertyName;
            }
            $propertiesSchemas[$propertyName] = $this->getPropertySchema($propertyReflection);
        }
        return (new ObjectSchemaBuilder())
            ->withPropertiesSchemas($propertiesSchemas)
            ->withRequiredProperties($requiredProperties);
    }

    private function isPropertyRequired(ReflectionProperty $propertyReflection, array $objectDefaultValues): bool
    {
        // TODO: not most effective solution, because we read annotations again in `getPropertySchema`
        $annotations = (array) $this->annotationReader->getPropertyAnnotations($propertyReflection);
        /** @var ?OA\Property $annotation */
        $annotation = $this->findAnnotation($annotations, OA\Property::class);
        if ($annotation && $annotation->required !== null) {
            return $annotation->required;
        }

        return ! \array_key_exists($propertyReflection->getName(), $objectDefaultValues);
    }

    private function createUnionSchemaBuilder(
        UnionVariableType $unionVariableType,
        array $annotations
    ): AbstractSchemaBuilder {
        throw new Exception('Union types are not supported');
    }

    /**
     * @return ?mixed
     */
    private function findAnnotation(
        array $annotations,
        string $annotationClass,
        bool $exceptionOnAnotherValueInterface = true
    ) {
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
