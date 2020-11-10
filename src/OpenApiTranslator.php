<?php

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\AbstractValueSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use Nette\SmartObject;

final class OpenApiTranslator implements OpenApiTranslatorInterface
{
    use SmartObject;

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
        // TODO maybe create GenericSchema as new generic parent?
        if ($valueSchema instanceof AbstractValueSchema) {
            $definition += $this->translateGenericProperties($valueSchema);
        }
        return $definition;
    }

    private function translateStringSchema(StringSchema $schema): array
    {
        $definition = [
            'type' => 'string',
        ];
        if ($schema->minLength !== null) {
            $definition['minLength'] = $schema->minLength;
        }
        if ($schema->maxLength !== null) {
            $definition['maxLength'] = $schema->maxLength;
        }
        if ($schema->format !== null) {
            $definition['format'] = $schema->format;
        }
        if ($schema->pattern !== null) {
            $definition['pattern'] = $schema->pattern;
        }
        return $definition;
    }

    private function translateIntegerSchema(IntegerSchema $schema): array
    {
        $definition =  [
            'type' => 'integer',
            'format' => 'int32', // JSON use 32bit integers
        ];
        if ($schema->minimum !== null) {
            $definition['minimum'] = $schema->minimum;
        }
        if ($schema->maximum !== null) {
            $definition['maximum'] = $schema->maximum;
        }
        if ($schema->exclusiveMinimum !== null) {
            $definition['exclusiveMinimum'] = $schema->exclusiveMinimum;
        }
        if ($schema->exclusiveMaximum !== null) {
            $definition['exclusiveMaximum'] = $schema->exclusiveMaximum;
        }
        if ($schema->multipleOf !== null) {
            $definition['multipleOf'] = $schema->multipleOf;
        }
        return $definition;
    }

    private function translateFloatSchema(FloatSchema $schema): array
    {
        $definition =  [
            'type' => 'number',
            'format' => 'float',
        ];
        if ($schema->minimum !== null) {
            $definition['minimum'] = $schema->minimum;
        }
        if ($schema->maximum !== null) {
            $definition['maximum'] = $schema->maximum;
        }
        if ($schema->exclusiveMinimum !== null) {
            $definition['exclusiveMinimum'] = $schema->exclusiveMinimum;
        }
        if ($schema->exclusiveMaximum !== null) {
            $definition['exclusiveMaximum'] = $schema->exclusiveMaximum;
        }
        if ($schema->multipleOf !== null) {
            $definition['multipleOf'] = $schema->multipleOf;
        }
        return $definition;
    }

    private function translateBooleanSchema(BooleanSchema $schema): array
    {
        $definition = [
            'type' => 'boolean',
        ];
        return $definition;
    }

    private function translateArraySchema(ArraySchema $schema): array
    {
        $definition = [
            'type' => 'array',
            'items' => $this->translateValueSchema($schema->itemsSchema),
        ];
        if ($schema->minItems) {
            $definition['minItems'] = $schema->minItems;
        }
        if ($schema->maxItems) {
            $definition['maxItems'] = $schema->maxItems;
        }
        if ($schema->uniqueItems) {
            $definition['uniqueItems'] = $schema->uniqueItems;
        }
        return $definition;
    }

    private function translateObjectSchema(ObjectSchema $schema): array
    {
        // TODO: tady se jeste musi vyresit prace s classes - protoze ty budeme potrebovat
        $definition = [
            'type' => 'object',
        ];
        // we also support "free-form objects without defined properties
        if ($schema->propertiesSchemas) {
            // we use property of `array_map` function that preserve keys
            $definition['properties'] = array_map(fn (ValueSchemaInterface $property) => $this->translateValueSchema($property), $schema->propertiesSchemas);

            if ($schema->requiredProperties) {
                $definition['required'] = $schema->requiredProperties;
            }
        }
        return $definition;
    }

    private function translateEnumSchema(EnumSchema $schema): array
    {
        $enum = $schema->enum;
        if ($schema->nullable) {
            if (!in_array(null, $enum, true)) {
                $enum[] = null; // null must be in enum to work with nullable type
            }
        }
        $definition = [
            'type' => 'string',
            'enum' => $enum,
        ];
        return $definition;
    }

    private function translateHashmapSchema(HashmapSchema $schema): array
    {
        $definition = [
            'type' => 'object',
        ];
        if ($schema->itemsSchema) {
            $definition['additionalProperties'] = $this->translateValueSchema($schema->itemsSchema);
        } else {
            $definition['additionalProperties'] = true; // "free-form" hashmap
        }
        return $definition;
    }

    private function translateGenericProperties(AbstractValueSchema $schema): array
    {
        $definition = ['nullable' => $schema->nullable];
        if ($schema->description) {
            $definition['description'] = $schema->description;
        }
        return $definition;
    }
}
