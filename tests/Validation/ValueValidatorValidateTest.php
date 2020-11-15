<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Tests\Validation\_Support\AssertViolationTrait;
use ScrumWorks\OpenApiSchema\Tests\Validation\_Support\CreateValidatorTrait;
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

class ValueValidatorValidateTest extends TestCase
{
    use AssertViolationTrait;
    use CreateValidatorTrait;

    /**
     * @dataProvider dpTestArray
     * @dataProvider dpTestBoolean
     * @dataProvider dpTestEnum
     * @dataProvider dpTestFloat
     * @dataProvider dpTestInteger
     * @dataProvider dpTestObject
     * @dataProvider dpTestHashmap
     * @dataProvider dpTestString
     * @dataProvider dpTestCombined
     *
     * @param mixed $data
     * @param array<int, array<mixed>> $expectedViolations
     */
    public function test(ValueSchemaInterface $schema, $data, array $expectedViolations): void
    {
        $result = $this->createValueValidator()->validate($schema, $data);
        $actualViolations = $result->getViolations();

        $this->assertViolations($expectedViolations, $actualViolations);
        $this->assertSame(empty($expectedViolations), $result->isValid(), 'isValid is different');
    }

    public function dpTestArray(): array
    {
        return [
            'array:valid' => [new ArraySchema(new IntegerSchema()), [1, 2, 3], []],
            'array:valid-null' => [new ArraySchema(new IntegerSchema(), null, null, null, true), null, []],
            'array:items-validation' => [
                new ArraySchema(new IntegerSchema()),
                ['a', null],
                [[1002, "Type 'integer' expected.", '[0]'], [1001, 'Unexpected NULL value.', '[1]']],
            ],
            'array:null' => [new ArraySchema(new IntegerSchema()), null, [[1001, 'Unexpected NULL value.', '']]],
            'array:type' => [
                new ArraySchema(new IntegerSchema()),
                'string',
                [[1002, "Type 'array' expected.", '']],
            ],
            'array:minItems' => [
                new ArraySchema(new IntegerSchema(), 4),
                [1],
                [[1005, 'Items count has to be at least 4.', '']],
            ],
            'array:maxItems' => [
                new ArraySchema(new IntegerSchema(), null, 1),
                [1, 2],
                [[1006, 'Items count has to be at most 1.', '']],
            ],
            'array:unique' => [
                new ArraySchema(new IntegerSchema(), null, null, true),
                [1, 2, 2],
                [[1007, 'Items have to be unique.', '']],
            ],
        ];
    }

    public function dpTestBoolean(): array
    {
        return [
            'bool:valid' => [new BooleanSchema(), true, []],
            'bool:valid-null' => [new BooleanSchema(true), null, []],
            'bool:null' => [new BooleanSchema(), null, [[1001, 'Unexpected NULL value.', '']]],
            'bool:type' => [new BooleanSchema(), 'string', [[1002, "Type 'boolean' expected.", '']]],
        ];
    }

    public function dpTestEnum(): array
    {
        return [
            'enum:valid' => [new EnumSchema(['a', 'b', 'c']), 'b', []],
            'enum:valid-null' => [new EnumSchema(['a', 'b', 'c'], true), null, []],
            'enum:null' => [new EnumSchema(['a']), null, [[1001, 'Unexpected NULL value.', '']]],
            'enum:type' => [new EnumSchema(['a']), false, [[1002, "Type 'string' expected.", '']]],
            'enum:invalid-choice' => [
                new EnumSchema(['a', 'b']),
                'c',
                [[1008, "Value has to be one of ['a', 'b'].", '']], ],
        ];
    }

    public function dpTestFloat(): array
    {
        return [
            'float:valid' => [new FloatSchema(), 1.0, []],
            'float:valid-null' => [new FloatSchema(null, null, null, null, null, true), null, []],
            'float:valid-min' => [new FloatSchema(3.0), 3.0, []],
            'float:valid-ex-min' => [new FloatSchema(3.0), 5.0, []],
            'float:valid-max' => [new FloatSchema(null, 3.0), 3.0, []],
            'float:valid-ex-max' => [new FloatSchema(null, 3.0), 2.0, []],
            'float:valid-multipleOf' => [new FloatSchema(null, null, null, null, 2.3), 4.6, []],
            'float:int' => [new FloatSchema(), 1, []],
            'float:null' => [new FloatSchema(), null, [[1001, 'Unexpected NULL value.', '']]],
            'float:type' => [new FloatSchema(), '1.0', [[1002, "Type 'number' expected.", '']]],
            'float:min' => [new FloatSchema(1.0), 0.5, [[1009, 'Value has to be bigger or equal then 1.', '']]],
            'float:ex-min' => [new FloatSchema(1.0, null, true), 0.5, [[1010, 'Value has to be bigger then 1.', '']]],
            'float:max' => [new FloatSchema(null, 1.0), 1.5, [[1011, 'Value has to be less or equal then 1.', '']]],
            'float:ex-max' => [
                new FloatSchema(null, 1.0, null, true),
                1.5,
                [[1012, 'Value has to be less then 1.', '']],
            ],
            'float:multipleOf' => [
                new FloatSchema(null, null, null, null, 2.3),
                5.5,
                [[1013, 'Value has to be divisible by 2.3.', '']],
            ],
        ];
    }

    public function dpTestInteger(): array
    {
        return [
            // int
            'int:valid' => [new IntegerSchema(), 1, []],
            'int:valid-null' => [new IntegerSchema(null, null, null, null, null, true), null, []],
            'int:valid-min' => [new IntegerSchema(3), 3, []],
            'int:valid-ex-min' => [new IntegerSchema(3, null, true), 5, []],
            'int:valid-max' => [new IntegerSchema(null, 3), 3, []],
            'int:valid-ex-max' => [new IntegerSchema(null, 3, null, true), 2, []],
            'int:valid-multipleOf' => [new IntegerSchema(null, null, null, null, 2), 4, []],
            'int:null' => [new IntegerSchema(), null, [[1001, 'Unexpected NULL value.', '']]],
            'int:type' => [new IntegerSchema(), 1.0, [[1002, "Type 'integer' expected.", '']]],
            'int:min' => [new IntegerSchema(3), 2, [[1009, 'Value has to be bigger or equal then 3.', '']]],
            'int:ex-min' => [new IntegerSchema(3, null, true), 2, [[1010, 'Value has to be bigger then 3.', '']]],
            'int:max' => [new IntegerSchema(null, 1), 5, [[1011, 'Value has to be less or equal then 1.', '']]],
            'int:ex-max' => [new IntegerSchema(null, 1, null, true), 5, [[1012, 'Value has to be less then 1.', '']]],
            'int:multipleOf' => [
                new IntegerSchema(null, null, null, null, 2),
                5,
                [[1013, 'Value has to be divisible by 2.', '']],
            ],
        ];
    }

    public function dpTestObject(): array
    {
        return [
            'object:valid' => [
                new ObjectSchema([
                    'a' => new IntegerSchema(),
                    'b' => new IntegerSchema(),
                ], ['a']),
                (object) [
                    'a' => 1,
                    'b' => 2,
                ],
                [],
            ],
            'object:valid-null' => [new ObjectSchema([], [], true, null), null, []],
            'object:null' => [
                new ObjectSchema([
                    'a' => new IntegerSchema(),
                ]),
                null,
                [[1001, 'Unexpected NULL value.', '']],
            ],
            'object:type' => [
                new ObjectSchema([
                    'a' => new IntegerSchema(),
                ]),
                1.0,
                [[1002, "Type 'object' expected.", '']],
            ],
            'object:required' => [
                new ObjectSchema([
                    'propertyName1' => new IntegerSchema(),
                ], ['propertyName1']),
                (object) [],
                [[1003, 'Required.', 'propertyName1']],
            ],
            'object:unexpected' => [
                new ObjectSchema([
                    'propertyName1' => new IntegerSchema(),
                ]),
                (object) [
                    'propertyNameUnknown' => 123,
                ],
                [[1004, 'Unexpected.', 'propertyNameUnknown']],
            ],
            'object:items-validation' => [
                new ObjectSchema([
                    'propertyName1' => new IntegerSchema(),
                ]),
                (object) [
                    'propertyName1' => false,
                ],
                [[1002, "Type 'integer' expected.", 'propertyName1']],
            ],
        ];
    }

    public function dpTestHashmap(): array
    {
        return [
            'hashmap:valid' => [
                new HashmapSchema(new IntegerSchema(), ['a']),
                (object) [
                    'a' => 1,
                    'b' => 2,
                ],
                [],
            ],
            'hashmap:valid-null' => [new HashmapSchema(new IntegerSchema(), [], true), null, []],
            'hashmap:null' => [
                new HashmapSchema(new IntegerSchema()),
                null,
                [[1001, 'Unexpected NULL value.', '']],
            ],
            'hashmap:type' => [
                new HashmapSchema(new IntegerSchema()),
                1.0,
                [[1002, "Type 'object' expected.", '']],
            ],
            'hashmap:required' => [
                new HashmapSchema(new IntegerSchema(), ['propertyName1']),
                (object) [],
                [[1003, 'Required.', 'propertyName1']],
            ],
            'hashmap:items-validation' => [
                new HashmapSchema(new IntegerSchema(), [], false, null),
                (object) [
                    'propertyName1' => false,
                ],
                [[1002, "Type 'integer' expected.", 'propertyName1']],
            ],
        ];
    }

    public function dpTestString(): array
    {
        return [
            // string
            'string:valid' => [new StringSchema(), 'hello', []],
            'string:valid-null' => [new StringSchema(null, null, null, null, true), null, []],
            'string:valid-minLength' => [new StringSchema(1), 'aa', []],
            'string:valid-maxLength' => [new StringSchema(null, 3), 'aa', []],
            'string:valid-format' => [
                new StringSchema(null, null, 'date-time'),
                '2020-01-02T12:30:44.09+00:30',
                [],
            ],
            'string:valid-pattern' => [new StringSchema(null, null, null, '[0-9]+'), '2020', []],
            'string:null' => [new StringSchema(), null, [[1001, 'Unexpected NULL value.', '']]],
            'string:type' => [new StringSchema(), 1.0, [[1002, "Type 'string' expected.", '']]],
            'string:minLength' => [new StringSchema(3), 'nn', [[1014, 'Characters count has to be at least 3.', '']]],
            'string:maxLength' => [
                new StringSchema(null, 1),
                'nn',
                [[1015, 'Characters count has to be at most 1.', '']], ],
            'string:format' => [
                new StringSchema(null, null, 'date-time'),
                '2020-12-30',
                [[1016, "Value doesn't have format 'date-time'.", '']],
            ],
            'string:pattern' => [
                new StringSchema(null, null, null, '-[0-9]{3}'),
                '2020-12-30',
                [[1017, "Value doesn't match pattern '-[0-9]{3}'.", '']],
            ],
        ];
    }

    public function dpTestMixed(): array
    {
        return [
            'mixed:valid-string' => [new MixedSchema(), 'hello', []],
            'mixed:valid-int' => [new MixedSchema(), 1, []],
            'mixed:valid-float' => [new MixedSchema(), 1.0, []],
            'mixed:valid-bool' => [new MixedSchema(), true, []],
            'mixed:valid-null' => [new MixedSchema(), null, []],
            'mixed:null' => [new MixedSchema(true), null, [[1001, 'Unexpected NULL value.', '']]],
        ];
    }

    public function dpTestCombined(): array
    {
        return [
            // multiple
            'multiple' => [
                new ObjectSchema([
                    'int1' => new IntegerSchema(),
                    'int2' => new IntegerSchema(),
                    'str' => new StringSchema(100),
                    'obj' => new ObjectSchema([
                        'arr' => new ArraySchema(new IntegerSchema()),
                    ]),
                    'reqProp' => new StringSchema(),
                ], ['reqProp']),
                (object) [
                    'int1' => false,
                    'int2' => 123,
                    'str' => 'hello world',
                    'obj' => (object) [
                        'arr' => [1, false, 3],
                    ],
                ],
                [
                    [1003, 'Required.', 'reqProp'],
                    [1002, "Type 'integer' expected.", 'int1'],
                    [1014, 'Characters count has to be at least 100.', 'str'],
                    [1002, "Type 'integer' expected.", 'obj.arr[1]'],
                ],
            ],
        ];
    }
}
