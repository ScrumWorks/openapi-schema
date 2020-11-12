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
                new MixedSchema(true, 'mixed'),
                [
                    'nullable' => true,
                    'description' => 'mixed',
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
                new IntegerSchema(0, 10, false, true, 2, false, 'integer'),
                [
                    'type' => 'integer',
                    'minimum' => 0,
                    'maximum' => 10,
                    'exclusiveMinimum' => false,
                    'exclusiveMaximum' => true,
                    'multipleOf' => 2,
                    'description' => 'integer',
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
                new FloatSchema(2.2, 2.8, true, false, 0.2, true, 'float'),
                [
                    'type' => 'number',
                    'format' => 'float',
                    'minimum' => 2.2,
                    'maximum' => 2.8,
                    'exclusiveMinimum' => true,
                    'exclusiveMaximum' => false,
                    'multipleOf' => 0.2,
                    'description' => 'float',
                    'nullable' => true,
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
                new BooleanSchema(true, 'boolean'),
                [
                    'type' => 'boolean',
                    'description' => 'boolean',
                    'nullable' => true,
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
                new StringSchema(2, 10, 'email', '[a-z]+', true, 'string'),
                [
                    'type' => 'string',
                    'minLength' => 2,
                    'maxLength' => 10,
                    'format' => 'email',
                    'pattern' => '[a-z]+',
                    'description' => 'string',
                    'nullable' => true,
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
                new EnumSchema(['value', 'value2'], false, 'enum'),
                [
                    'type' => 'string',
                    'enum' => ['value', 'value2'],
                    'description' => 'enum',
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
                new ArraySchema(new StringSchema(), 1, 3, true, false, 'array', ),
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                    'minItems' => 1,
                    'maxItems' => 3,
                    'uniqueItems' => true,
                    'description' => 'array',
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
                new HashmapSchema(new StringSchema(null, null, null, null, true)),
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
                new HashmapSchema(new IntegerSchema(), ['property'], false, 'hashmap'),
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
                ], ['name'], false, 'object'),
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
                ],
            ],
        ];
    }
}
