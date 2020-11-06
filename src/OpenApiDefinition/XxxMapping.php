<?php



namespace Lang\OpenApiDefinition;

use Lang\OpenApiDefinition\ValueSchema\Builder\AbstractSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\BooleanSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\FloatSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\IntegerSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\ObjectSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\StringSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\ObjectSchema;
use Lang\OpenApiDefinition\ValueSchema\ValueSchemaInterface;
use Nette\Utils\Reflection;

final class XxxMapping
{
    public function xxx(\ReflectionObject $obj): ObjectSchema
    {
        $propertiesSchemas = [];
        $class = $obj->getName();
        foreach ($obj->getProperties() as $property) {
            // If property is not from the latest child, then skip it.
            if ($property->getDeclaringClass()->getName() !== $class) {
                continue;
            }

            // If property is not public, then skip it.
            if (!$property->isPublic()) {
                continue;
            }

            $propertiesSchemas[$property->getName()] = $this->getPropertySchema($property);
        }
        return (new ObjectSchemaBuilder())->withPropertiesSchemas($propertiesSchemas)->build();
    }

    private function getPropertySchema(\ReflectionProperty $property): ValueSchemaInterface
    {
        $infoFromType = $this->getPropertyInfoFromType($property);
        $infoFromPhpDoc = $this->getPropertyInfoFromPhpDoc($property);

        if ($infoFromType === null && $infoFromPhpDoc === null) {
            // throw exception??
        }

        if ($infoFromType === null) {
            $infoFromType = $infoFromPhpDoc;
        }
        if ($infoFromPhpDoc === null) {
            $infoFromPhpDoc = $infoFromType;
        }

        $mergedInfo = $this->mergePropertyInfos($infoFromType, $infoFromPhpDoc);

        switch ($mergedInfo['typeInfo']['general']) {
            case 'scalar':
                $schemaBuilder = $this->createSchemaBuilderFromScalar($mergedInfo['typeInfo']['type'], $property);
            case 'array':
                $schemaBuilder = $this->createSchemaBuilderFromArray($mergedInfo['typeInfo']['type'], $property);
        }
        // @TODO also solve @description
        return $schemaBuilder->withNullable($mergedInfo['nullable'])->build();
    }

    private function createSchemaFromPropertyInfo(array $info, \ReflectionProperty $property): ValueSchemaInterface
    {
        switch ($info['general']) {
            case 'scalar':
                $schemaBuilder = $this->createSchemaBuilderFromScalar($info['typeInfo']['type'], $property);
            case 'array':
                $schemaBuilder = $this->createSchemaBuilderFromArray($info['typeInfo']['type'], $property);
        }
        // @TODO also solve @description
        return $schemaBuilder->withNullable($mergedInfo['nullable'])->build();
    }

    private function createSchemaBuilderFromScalar(string $scalarType, \ReflectionProperty $property): AbstractSchemaBuilder
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

    private function createSchemaBuilderFromArray(array $arraySchema, \ReflectionProperty $property): AbstractSchemaBuilder
    {
        var_dump($arraySchema);die;
    }

    private function mergePropertyInfos(array $A, array $B): array
    {
        if ($A === $B) {
            return $A;
        }

        if ($A['nullable'] !== $B['nullable']) {
            throw new \Exception(sprintf('Incompatible nullable types in property and phpdoc'));
        }

        if ($A['typeInfo']['general'] !== $B['typeInfo']['general']) {
            throw new \Exception(sprintf('Incompatible types in property (%s) and phpdoc (%s)', $A['type'], $B['type']));
        }

        switch ($A['typeInfo']['general']) {
            case 'scalar':
                if ($A['typeInfo']['type'] !== $B['typeInfo']['type']) {
                    throw new \Exception(sprintf('Incompatible types in property (%s) and phpdoc (%s)', $A['type'], $B['type']));
                }
                return $A;
            case 'array':
                $mergedArray = $this->tryMergeArrayType($A['typeInfo']['type'], $B['typeInfo']['type']);
                if ($mergedArray === null) {
                    throw new \Exception(sprintf('Incompatible types in property (%s) and phpdoc (%s)', $A['type'], $B['type']));
                }
                $A['typeInfo']['type'] = $mergedArray;
                return $A;
        }
    }

    private function tryMergeArrayType($A, $B): ?array
    {
        if ($A['general'] === 'mixed') {
            return $B;
        }
        if ($B['general'] === 'mixed') {
            return $A;
        }
        if ($A['general'] !== $B['general']) {
            return null;
        }
        // todo solve merging same general types
    }

    private function getPropertyInfoFromType(\ReflectionProperty $property): ?array
    {
        if (($propertyType = $property->getType()) instanceof \ReflectionNamedType) {
            $type = $this->expandClassName($propertyType->getName(), $property);
            // generalType
            $nullable = $propertyType->allowsNull();

            return ['type' => $type, 'nullable' => $nullable, 'typeInfo' => $this->analyzeType($type, $property)];
        }

        return null;
    }

    private function getPropertyInfoFromPhpDoc(\ReflectionProperty $property): ?array
    {
        $varAnnotation = $this->parseAnnotation($property, 'var');
        if ($varAnnotation === null) {
            return null;
        }

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
        $phpDocInfo['typeInfo'] = $this->analyzeType($phpDocInfo['type'], $property);
        //$phpDocInfo['generalType'] = $this->getPropertyGeneralType($phpDocInfo['type']);
        return $phpDocInfo;
    }

    private function analyzeType(string $type, \ReflectionProperty $property): array
    {
        if ($type === 'mixed') {
            return ['general' => 'mixed'];
        }
        if ($result = $this->tryIsScalar($type)) {
            return $result;
        }
        if ($result = $this->tryIsArray($type, $property)) {
            return $result;
        }
        if ($result = $this->tryIsObject($type)) {
            return $result;
        }
        throw new \Exception(sprintf('Unknown type "%s"', $type));
    }

    private function tryIsScalar(string $type): ?array
    {
        switch ($type) {
            case 'int':
            case 'integer':
                return ['general' => 'scalar', 'type' => 'integer']; // TODO enum
            case 'float':
                return ['general' => 'scalar', 'type' => 'float'];
            case 'bool':
            case 'boolean':
                return ['general' => 'scalar', 'type' => 'boolean'];
            case 'string':
                return ['general' => 'scalar', 'type' => 'string'];
        }
        return null;
    }

    private function tryIsArray(string $type, \ReflectionProperty $property): ?array
    {
        if ($type === 'array') {
            return ['general' => 'array', 'type' => ['general' => 'mixed']];
        }

        if (substr($type, -2) === '[]') {
            $arrayType = substr($type, 0, -2);
            $arrayType = $this->expandClassName($arrayType, $property);
            return ['general' => 'array', 'type' => $this->analyzeType($arrayType, $property)];
        }

        // TODO also support array<int>

        return null;
    }

    private function tryIsObject(string $type): ?array
    {
        if (class_exists($type)) {
            return ['general' => 'object', 'type' => $type];
        }

        return null;
    }

    private function expandClassName(string $str, \ReflectionProperty $property): string
    {
        return Reflection::expandClassName($str, Reflection::getPropertyDeclaringClass($property));
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
}
