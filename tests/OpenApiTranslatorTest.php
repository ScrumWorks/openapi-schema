<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\OpenApiTranslator;
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

class OpenApiTranslatorTest extends TestCase
{
    private OpenApiTranslator $translator;

    protected function setUp(): void
    {
        $this->translator = new OpenApiTranslator();
    }

    /**
     * @dataProvider dpMixedArray
     * @dataProvider dpIntegerArray
     * @dataProvider dpFloatArray
     * @dataProvider dpBooleanArray
     * @dataProvider dpStringArray
     * @dataProvider dpEnumArray
     * @dataProvider dpArrayArray
     * @dataProvider dpHashmapArray
     * @dataProvider dpObjectArray
     */
    public function test(ValueSchemaInterface $schema, array $expected): void
    {
        $this->assertEquals($expected, $this->translator->translateValueSchema($schema));
    }

    public function dpMixedArray(): array
    {
        return [
            'mixed:minimal' => [new MixedSchema(), []],
            'mixed:full' => [
                new MixedSchema(
                    nullable: true,
                    description: 'mixed',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'nullable' => true,
                    'description' => 'mixed',
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public function dpIntegerArray(): array
    {
        return [
            'integer:minimal' => [
                new IntegerSchema(),
                [
                    'type' => 'integer',
                ],
            ],
            'integer:full' => [
                new IntegerSchema(
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

    public function dpFloatArray(): array
    {
        return [
            'float:minimal' => [
                new FloatSchema(),
                [
                    'type' => 'number',
                    'format' => 'float',
                ],
            ],
            'float:full' => [
                new FloatSchema(
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

    public function dpBooleanArray(): array
    {
        return [
            'boolean:minimal' => [
                new BooleanSchema(),
                [
                    'type' => 'boolean',
                ],
            ],
            'boolean:full' => [
                new BooleanSchema(
                    nullable: true,
                    description: 'boolean',
                    schemaName: null,
                    isDeprecated: true,
                ),
                [
                    'type' => 'boolean',
                    'description' => 'boolean',
                    'nullable' => true,
                    'deprecated' => true,
                ],
            ],
        ];
    }

    public function dpStringArray(): array
    {
        return [
            'string:minimal' => [
                new StringSchema(),
                [
                    'type' => 'string',
                ],
            ],
            'string:full' => [
                new StringSchema(
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

    public function dpEnumArray(): array
    {
        return [
            'enum:minimal' => [
                new EnumSchema(['value']),
                [
                    'type' => 'string',
                    'enum' => ['value'],
                ],
            ],
            'enum:nullable' => [
                new EnumSchema(['value'], true),
                [
                    'type' => 'string',
                    'enum' => ['value', null],
                    'nullable' => true,
                ],
            ],
            'enum:full' => [
                new EnumSchema(
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
        ];
    }

    public function dpArrayArray(): array
    {
        return [
            'array:simple' => [
                new ArraySchema(new StringSchema()),
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'array:mixed' => [
                new ArraySchema(new MixedSchema()),
                [
                    'type' => 'array',
                    'items' => [],
                ],
            ],
            'array:full' => [
                new ArraySchema(
                    itemsSchema: new StringSchema(),
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
                new ArraySchema(new ArraySchema(new IntegerSchema())),
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

    public function dpHashmapArray(): array
    {
        return [
            'hashmap:minimal' => [
                new HashmapSchema(new StringSchema(nullable: true)),
                [
                    'type' => 'object',
                    'additionalProperties' => [
                        'type' => 'string',
                        'nullable' => true,
                    ],
                ],
            ],
            'hashmap:free-form' => [
                new HashmapSchema(new MixedSchema()),
                [
                    'type' => 'object',
                    'additionalProperties' => true,
                ],
            ],
            'hashmap:full' => [
                new HashmapSchema(
                    itemsSchema: new IntegerSchema(),
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

    public function dpObjectArray(): array
    {
        return [
            'object:minimal' => [
                new ObjectSchema([
                    'name' => new StringSchema(),
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
                new ObjectSchema([]),
                [
                    'type' => 'object',
                    'additionalProperties' => true,
                ],
            ],
            'object:full' => [
                new ObjectSchema([
                    'name' => new StringSchema(),
                    'age' => new IntegerSchema(),
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
