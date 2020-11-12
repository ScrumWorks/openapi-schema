<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class OpenApiTranslator implements OpenApiTranslatorInterface
{
    public function translateValueSchema(ValueSchemaInterface $valueSchema): array
    {
        $definition = [];
        if ($valueSchema instanceof StringSchema) {
            $definition += $this->translateStringSchema($valueSchema);
        } elseif ($valueSchema instanceof IntegerSchema) {
            $definition += $this->translateIntegerSchema($valueSchema);
        } elseif ($valueSchema instanceof FloatSchema) {
            $definition += $this->translateFloatSchema($valueSchema);
        } elseif ($valueSchema instanceof BooleanSchema) {
            $definition += $this->translateBooleanSchema($valueSchema);
        } elseif ($valueSchema instanceof ArraySchema) {
            $definition += $this->translateArraySchema($valueSchema);
        } elseif ($valueSchema instanceof ObjectSchema) {
            $definition += $this->translateObjectSchema($valueSchema);
        } elseif ($valueSchema instanceof EnumSchema) {
            $definition += $this->translateEnumSchema($valueSchema);
        } elseif ($valueSchema instanceof HashmapSchema) {
            $definition += $this->translateHashmapSchema($valueSchema);
        }
        $definition += $this->translateGenericProperties($valueSchema);
        return $definition;
    }

    private function translateStringSchema(StringSchema $schema): array
    {
        $definition = [
            'type' => 'string',
        ];
        if ($schema->getMinLength() !== null) {
            $definition['minLength'] = $schema->getMinLength();
        }
        if ($schema->getMaxLength() !== null) {
            $definition['maxLength'] = $schema->getMaxLength();
        }
        if ($schema->getFormat() !== null) {
            $definition['format'] = $schema->getFormat();
        }
        if ($schema->getPattern() !== null) {
            $definition['pattern'] = $schema->getPattern();
        }
        return $definition;
    }

    private function translateIntegerSchema(IntegerSchema $schema): array
    {
        $definition = [
            'type' => 'integer',
            // JSON use 32bit integers
            'format' => 'int32',
        ];
        if ($schema->getMinimum() !== null) {
            $definition['minimum'] = $schema->getMinimum();
        }
        if ($schema->getMaximum() !== null) {
            $definition['maximum'] = $schema->getMaximum();
        }
        if ($schema->getExclusiveMinimum() !== null) {
            $definition['exclusiveMinimum'] = $schema->getExclusiveMinimum();
        }
        if ($schema->getExclusiveMaximum() !== null) {
            $definition['exclusiveMaximum'] = $schema->getExclusiveMaximum();
        }
        if ($schema->getMultipleOf() !== null) {
            $definition['multipleOf'] = $schema->getMultipleOf();
        }
        return $definition;
    }

    private function translateFloatSchema(FloatSchema $schema): array
    {
        $definition = [
            'type' => 'number',
            'format' => 'float',
        ];
        if ($schema->getMinimum() !== null) {
            $definition['minimum'] = $schema->getMinimum();
        }
        if ($schema->getMaximum() !== null) {
            $definition['maximum'] = $schema->getMaximum();
        }
        if ($schema->getExclusiveMinimum() !== null) {
            $definition['exclusiveMinimum'] = $schema->getExclusiveMinimum();
        }
        if ($schema->getExclusiveMaximum() !== null) {
            $definition['exclusiveMaximum'] = $schema->getExclusiveMaximum();
        }
        if ($schema->getMultipleOf() !== null) {
            $definition['multipleOf'] = $schema->getMultipleOf();
        }
        return $definition;
    }

    private function translateBooleanSchema(BooleanSchema $schema): array
    {
        return [
            'type' => 'boolean',
        ];
    }

    private function translateArraySchema(ArraySchema $schema): array
    {
        $definition = [
            'type' => 'array',
            'items' => $this->translateValueSchema($schema->getItemsSchema()),
        ];
        if ($schema->getMinItems()) {
            $definition['minItems'] = $schema->getMinItems();
        }
        if ($schema->getMaxItems()) {
            $definition['maxItems'] = $schema->getMaxItems();
        }
        if ($schema->getUniqueItems()) {
            $definition['uniqueItems'] = $schema->getUniqueItems();
        }
        return $definition;
    }

    private function translateObjectSchema(ObjectSchema $schema): array
    {
        $definition = [
            'type' => 'object',
        ];
        if ($schema->getPropertiesSchemas()) {
            // we use property of `array_map` function that preserve keys
            $definition['properties'] = \array_map(
                fn (ValueSchemaInterface $property) => $this->translateValueSchema($property),
                $schema->getPropertiesSchemas()
            );

            if ($schema->getRequiredProperties()) {
                $definition['required'] = $schema->getRequiredProperties();
            }
        } else {
            // we also support "free-form objects without defined properties
            $definition['additionalProperties'] = true;
        }
        return $definition;
    }

    private function translateEnumSchema(EnumSchema $schema): array
    {
        $enum = $schema->getEnum();
        if ($schema->isNullable()) {
            // @phpstan-ignore-next-line
            $enum[] = null;
        }
        return [
            'type' => 'string',
            'enum' => $enum,
        ];
    }

    private function translateHashmapSchema(HashmapSchema $schema): array
    {
        $definition = [
            'type' => 'object',
        ];
        if ($schema->getRequiredProperties()) {
            $definition['properties'] = [];
            foreach ($schema->getRequiredProperties() as $property) {
                $definition['properties'][$property] = $this->translateValueSchema($schema->getItemsSchema());
            }
            $definition['required'] = $schema->getRequiredProperties();
        }
        if ($schema->getItemsSchema() instanceof MixedSchema) {
            // "free-form" hashmap
            $definition['additionalProperties'] = true;
        } else {
            $definition['additionalProperties'] = $this->translateValueSchema($schema->getItemsSchema());
        }
        return $definition;
    }

    private function translateGenericProperties(ValueSchemaInterface $schema): array
    {
        $definition = [];
        if ($schema->isNullable()) {
            $definition['nullable'] = true;
        }
        if ($schema->getDescription()) {
            $definition['description'] = $schema->getDescription();
        }
        return $definition;
    }
}
