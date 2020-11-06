<?php

namespace Lang\OpenApiDefinition;


use Apitte\Core\Exception\Logical\InvalidStateException;
use Lang\OpenApiDefinition\ValueSchema\Builder\AbstractSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\BooleanSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\FloatSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\IntegerSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\ObjectSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\StringSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\ValueSchemaInterface;
use Nette\Utils\Reflection;

/**
 * @TODO
 */
final class ObjectMapping
{
    public function map($class)
    {
        if (!class_exists($class)) {
            throw new \Exception();
        }


    }

    public function getSchema(\ReflectionObject $obj)
    {
        $propertiesSchemas = [];
        $class = $obj->getName();
        $defaultProperties = $obj->getDefaultProperties();
        foreach ($obj->getProperties() as $property) {
            // If property is not from the latest child, then skip it.
            if ($property->getDeclaringClass()->getName() !== $class) {
                continue;
            }

            // If property is not public, then skip it.
            if (!$property->isPublic()) {
                continue;
            }

            $propertyInfo = null;

            $phpDocInfo = null;

            if (($propertyType = $property->getType()) instanceof \ReflectionNamedType) {
                $type = $this->expandClassName($propertyType->getName(), $property);
                $propertyInfo = [
                    'type' => $type,
                    'generalType' => $this->getPropertyGeneralType($type),
                    'nullable' => $propertyType->allowsNull(),
                ];
            }

            $varAnnotation = $this->parseAnnotation($property, 'var');
            if ($varAnnotation !== null) {
                $phpDocInfo = [];

                // Normalize null type
                $varAnnotation = \preg_replace('/^\?/', 'null|', $varAnnotation);

                $types = explode('|', $varAnnotation);
                if (\array_search('null', $types, true)) {
                    $phpDocInfo['nullable'] = true;
                    $types = array_values(array_filter($types, static fn (string $type) => $type !== 'null'));
                } else {
                    $phpDocInfo['nullable'] = false;
                }
                if (count($types) > 1) {
                    throw new \Exception('Unsupported union types');
                }
                $phpDocInfo['type'] = $this->expandClassName($types[0], $property);
                $phpDocInfo['generalType'] = $this->getPropertyGeneralType($phpDocInfo['type']);
            }

            if ($propertyInfo === null && $phpDocInfo === null) {
                // mixed, null
            }

            if ($propertyInfo === null) {
                $propertyInfo = $phpDocInfo;
            } elseif ($phpDocInfo === null) {
                $phpDocInfo = $propertyInfo;
            }

            if ($propertyInfo['nullable'] !== $phpDocInfo['nullable']) {
                throw new \Exception(sprintf('Incompatible nullable types in property and phpdoc'));
            }

            if ($propertyInfo['generalType'] !== $phpDocInfo['generalType']) {
                throw new \Exception(sprintf('Incompatible types in property (%s) and phpdoc (%s)', $propertyInfo['type'], $phpDocInfo['type']));
            }

            switch ($propertyInfo['generalType']) {
                case 'scalar':
                    $propertyType = $this->unifyScalarType($propertyInfo['type']);
                    $phpDocType = $this->unifyScalarType($phpDocInfo['type']);

                    if ($propertyType !== $phpDocType) {
                        throw new \Exception(sprintf('Incompatible types in property (%s) and phpdoc (%s)', $propertyType, $phpDocType));
                    }

                    $schemaBuilder = $this->createSchemaFromScalar($propertyType, $property);
                    break;
                case 'array':
                    break;
                case 'object':
                    break;
            }
            $description = $this->parseAnnotationDescription($property);
            if ($description !== null) {
                $schemaBuilder = $schemaBuilder->withDescription($description);
            }
            $propertiesSchemas[$property->getName()] = $schemaBuilder->withNullable($propertyInfo['nullable'])->build();
        }
        return (new ObjectSchemaBuilder())->withPropertiesSchemas($propertiesSchemas)->build();
    }

    private function createSchemaFromScalar(string $scalarType, \ReflectionProperty $property): AbstractSchemaBuilder
    {
        switch ($scalarType) {
            case 'integer';
                $schemaBuilder = new IntegerSchemaBuilder();
                // TODO read params from PhpDoc
                break;
            case 'float':
                $schemaBuilder = new FloatSchemaBuilder();
                // TODO read params from PhpDoc
                break;
            case 'boolean':
                $schemaBuilder = new BooleanSchemaBuilder();
                break;
            case 'string':
                $schemaBuilder = new StringSchemaBuilder();
                // TODO read params from PhpDoc
                break;
        }
        return $schemaBuilder;
    }

    private function unifyScalarType(string $type): string
    {
        switch ($type) {
            case 'int':
            case 'integer':
                return 'integer'; // TODO: enum
            case 'float':
                return 'float';
            case 'bool':
            case 'boolean':
                return 'boolean';
            case 'string':
                return 'string';
        }
    }

    private function getPropertyGeneralType(string $type): string
    {
        static $scalars = [
            'int', 'integer', 'float', 'bool', 'boolean', 'string'
        ];
        if (in_array($type, $scalars)) {
            return 'scalar'; // TODO: enum
        }

        // add support for array<int> and array<int, string>
        if (substr($type, -2) === '[]' || $type === 'array') {
            return 'array';
        }

        if (class_exists($type)) {
            return 'class';
        }

        throw new \Exception(sprintf('Unknown type "%s"', $type));
    }

    /**
     * Returns an annotation value.
     * @param  \ReflectionFunctionAbstract|\ReflectionProperty|\ReflectionClass  $ref
     */
    private function parseAnnotation(\Reflector $ref, string $name): ?string
    {
        if (!Reflection::areCommentsAvailable()) {
            throw new \Nette\InvalidStateException('You have to enable phpDoc comments in opcode cache.');
        }
        $re = '#[\s*]@' . preg_quote($name, '#') . '(?=\s|$)(?:[ \t]+([^@\s]\S*))?#';
        if ($ref->getDocComment() && preg_match($re, trim($ref->getDocComment(), '/*'), $m)) {
            return $m[1] ?? '';
        }
        return null;
    }

    /**
     * @param \ReflectionClass|\ReflectionClassConstant|\ReflectionProperty|\ReflectionFunctionAbstract $ref
     */
    private function parseAnnotationDescription(\Reflector $ref): ?string
    {
        if (! Reflection::areCommentsAvailable()) {
            throw new \InvalidStateException('You have to enable phpDoc comments in opcode cache.');
        }
        $re = '#[\s*]@description[ \t]+(.+)#';
        if ($ref->getDocComment() && \preg_match($re, \trim($ref->getDocComment(), '/*'), $m)) {
            return $m[1] ?? null;
        }
        return null;
    }

    private function expandClassName(string $str, \ReflectionProperty $property): string
    {
        return Reflection::expandClassName($str, Reflection::getPropertyDeclaringClass($property));
    }



/*
    public function getProperties(): array
    {
        if (! $this->properties) {
            $properties = [];
            $rf = new ReflectionObject($this);
            $class = static::class;

            $defaultProperties = $rf->getDefaultProperties();
            foreach ($rf->getProperties() as $property) {
                // If property is not from the latest child, then skip it.
                if ($property->getDeclaringClass()->getName() !== $class) {
                    continue;
                }

                // If property is not public, then skip it.
                if (! $property->isPublic()) {
                    continue;
                }

                $nullable = false;
                $type = null;
                $subType = null;
                if (($propertyType = $property->getType()) instanceof ReflectionNamedType) {
                    $type = $propertyType->getName();
                    if ($propertyType->allowsNull()) {
                        $nullable = true;
                    }
                }

                // try to read type from PhpDoc
                // todo maybe add support for ? and |null in property annotation
                if ($type === null || $type === 'array') {
                    $var = $this->parseAnnotation($property, 'var');
                    if ($var !== null) {
                        $isArray = substr($var, -2) === '[]';
                        if ($isArray) {
                            $var = substr($var, 0, -2);
                        }
                        // try to expand classes
                        $expandedVar = Reflection::expandClassName($var, Reflection::getPropertyDeclaringClass($property));
                        if ($isArray || $type === 'array') {
                            $type = 'array';
                            if (class_exists($expandedVar)) {
                                $subType = ['type' => 'object', 'subtype' => $expandedVar];
                            } else {
                                $subType = ['type' => $expandedVar, 'subtype' => null];
                            }
                        } else {
                            if (class_exists($expandedVar)) {
                                $type = 'object';
                                $subType = $expandedVar;
                            }
                        }
                    } else {
                        if ($type === 'array') {
                            // we can't determine type of array
                            $subType = ['type' => 'mixed', 'subtype' => null];
                        } else {
                            // we can't determine type of property
                            $type = 'mixed';
                        }
                    }
                }

                $name = $property->getName();
                $properties[$name] = [
                    'name' => $name,
                    'type' => $type,
                    'subType' => $subType,
                    'defaultValue' => $defaultProperties[$name] ?? null,
                    'nullable' => $nullable,
                ];
            }

            $this->properties = $properties;
        }

        return $this->properties;
    }
*/
    /**
     * Returns an annotation value.
     * @param  \ReflectionFunctionAbstract|\ReflectionProperty|\ReflectionClass  $ref
     */
  /*  private function parseAnnotation(\Reflector $ref, string $name): ?string
    {
        if (!Reflection::areCommentsAvailable()) {
            throw new \Nette\InvalidStateException('You have to enable phpDoc comments in opcode cache.');
        }
        $re = '#[\s*]@' . preg_quote($name, '#') . '(?=\s|$)(?:[ \t]+([^@\s]\S*))?#';
        if ($ref->getDocComment() && preg_match($re, trim($ref->getDocComment(), '/*'), $m)) {
            return $m[1] ?? '';
        }
        return null;
    }*/
}
