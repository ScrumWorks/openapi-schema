<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\OpenApiTranslator;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ArraySchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\BooleanSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\EnumSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\FloatSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\HashmapSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\IntegerSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\MixedSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ObjectSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class OpenApiTranslatorTest extends TestCase
{
    private OpenApiTranslator $translator;

    protected function setUp(): void
    {
        $this->translator = new OpenApiTranslator();
    }

    #[DataProvider(methodName: 'dpMixedArray')]
    #[DataProvider(methodName: 'dpIntegerArray')]
    #[DataProvider(methodName: 'dpFloatArray')]
    #[DataProvider(methodName: 'dpBooleanArray')]
    #[DataProvider(methodName: 'dpStringArray')]
    #[DataProvider(methodName: 'dpEnumArray')]
    #[DataProvider(methodName: 'dpArrayArray')]
    #[DataProvider(methodName: 'dpHashmapArray')]
    #[DataProvider(methodName: 'dpObjectArray')]
    public function test(ValueSchemaInterface $schema, array $expected): void
    {
        $this->assertEquals($expected, $this->translator->translateValueSchema($schema));
    }

    public static function dpMixedArray(): array
    {
        return [
            'mixed:minimal' => [new MixedSchemaData(), []],
            'mixed:full' => [
                new MixedSchemaData(nullable: true, description: 'mixed', schemaName: null, isDeprecated: true),
                [
                    'nullable' => true,
                    'description' => 'mixed',
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public static function dpIntegerArray(): array
    {
        return [
            'integer:minimal' => [
                new IntegerSchemaData(),
                [
                    'type' => 'integer',
                ],
            ],
            'integer:full' => [
                new IntegerSchemaData(
                    minimum: 0,
                    maximum: 10,
                    exclusiveMinimum: false,
                    exclusiveMaximum: true,
                    multipleOf: 2,
                    example: 12345,
                    nullable: false,
                    description: 'integer',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'integer',
                    'minimum' => 0,
                    'maximum' => 10,
                    'exclusiveMinimum' => false,
                    'exclusiveMaximum' => true,
                    'multipleOf' => 2,
                    'example' => 12345,
                    'description' => 'integer',
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public static function dpFloatArray(): array
    {
        return [
            'float:minimal' => [
                new FloatSchemaData(),
                [
                    'type' => 'number',
                    'format' => 'float',
                ],
            ],
            'float:full' => [
                new FloatSchemaData(
                    minimum: 2.2,
                    maximum: 2.8,
                    exclusiveMinimum: true,
                    exclusiveMaximum: false,
                    multipleOf: 0.2,
                    example: 12.345,
                    nullable: true,
                    description: 'float',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'number',
                    'format' => 'float',
                    'minimum' => 2.2,
                    'maximum' => 2.8,
                    'exclusiveMinimum' => true,
                    'exclusiveMaximum' => false,
                    'multipleOf' => 0.2,
                    'example' => 12.345,
                    'description' => 'float',
                    'nullable' => true,
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public static function dpBooleanArray(): array
    {
        return [
            'boolean:minimal' => [
                new BooleanSchemaData(),
                [
                    'type' => 'boolean',
                ],
            ],
            'boolean:full' => [
                new BooleanSchemaData(nullable: true, description: 'boolean', schemaName: null, isDeprecated: true),
                [
                    'type' => 'boolean',
                    'description' => 'boolean',
                    'nullable' => true,
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public static function dpStringArray(): array
    {
        return [
            'string:minimal' => [
                new StringSchemaData(),
                [
                    'type' => 'string',
                ],
            ],
            'string:full' => [
                new StringSchemaData(
                    minLength: 2,
                    maxLength: 10,
                    format: 'email',
                    pattern: '[a-z]+',
                    example: 'example string',
                    nullable: true,
                    description: 'string',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'string',
                    'minLength' => 2,
                    'maxLength' => 10,
                    'format' => 'email',
                    'pattern' => '[a-z]+',
                    'example' => 'example string',
                    'description' => 'string',
                    'nullable' => true,
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public static function dpEnumArray(): array
    {
        return [
            'enum:minimal' => [
                new EnumSchemaData(['value']),
                [
                    'type' => 'string',
                    'enum' => ['value'],
                ],
            ],
            'enum:nullable' => [
                new EnumSchemaData(['value'], true),
                [
                    'type' => 'string',
                    'enum' => ['value', null],
                    'nullable' => true,
                ],
            ],
            'enum:full' => [
                new EnumSchemaData(
                    enum: ['value', 'value2'],
                    nullable: false,
                    description: 'enum',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'string',
                    'enum' => ['value', 'value2'],
                    'description' => 'enum',
                    'deprecated' => true,
                ],
            ],
            'enum:int' => [
                new EnumSchemaData([1, 2, 3]),
                [
                    'type' => 'int',
                    'enum' => [1, 2, 3],
                ],
            ],
        ];
    }

    public static function dpArrayArray(): array
    {
        return [
            'array:simple' => [
                new ArraySchemaData(new StringSchemaData()),
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'array:mixed' => [
                new ArraySchemaData(new MixedSchemaData()),
                [
                    'type' => 'array',
                    'items' => [],
                ],
            ],
            'array:full' => [
                new ArraySchemaData(
                    itemsSchema: new StringSchemaData(),
                    minItems: 1,
                    maxItems: 3,
                    uniqueItems: true,
                    nullable: false,
                    description: 'array',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                    'minItems' => 1,
                    'maxItems' => 3,
                    'uniqueItems' => true,
                    'description' => 'array',
                    'deprecated' => true,
                ],
            ],
            'array:nested' => [
                new ArraySchemaData(new ArraySchemaData(new IntegerSchemaData())),
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function dpHashmapArray(): array
    {
        return [
            'hashmap:minimal' => [
                new HashmapSchemaData(new StringSchemaData(nullable: true)),
                [
                    'type' => 'object',
                    'additionalProperties' => [
                        'type' => 'string',
                        'nullable' => true,
                    ],
                ],
            ],
            'hashmap:free-form' => [
                new HashmapSchemaData(new MixedSchemaData()),
                [
                    'type' => 'object',
                    'additionalProperties' => true,
                ],
            ],
            'hashmap:full' => [
                new HashmapSchemaData(
                    itemsSchema: new IntegerSchemaData(),
                    requiredProperties: ['property'],
                    nullable: false,
                    description: 'hashmap',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'object',
                    'properties' => [
                        'property' => [
                            'type' => 'integer',
                        ],
                    ],
                    'required' => ['property'],
                    'additionalProperties' => [
                        'type' => 'integer',
                    ],
                    'description' => 'hashmap',
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public static function dpObjectArray(): array
    {
        return [
            'object:minimal' => [
                new ObjectSchemaData([
                    'name' => new StringSchemaData(),
                ]),
                [
                    'type' => 'object',
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
            'object:free-form' => [
                new ObjectSchemaData([]),
                [
                    'type' => 'object',
                    'additionalProperties' => true,
                ],
            ],
            'object:full' => [
                new ObjectSchemaData([
                    'name' => new StringSchemaData(),
                    'age' => new IntegerSchemaData(),
                ], ['name'], false, 'object', null, true),
                [
                    'type' => 'object',
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                        ],
                        'age' => [
                            'type' => 'integer',
                        ],
                    ],
                    'required' => ['name'],
                    'description' => 'object',
                    'deprecated' => true,
                ],
            ],
        ];
    }
}
