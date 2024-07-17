<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Tests\Validation\_Support\AssertViolationTrait;
use ScrumWorks\OpenApiSchema\Tests\Validation\_Support\CreateValidatorTrait;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ArraySchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\BooleanSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\EnumSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\FloatSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\HashmapSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\IntegerSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\MixedSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ObjectSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\UnionSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class ValueValidatorValidateTest extends TestCase
{
    use AssertViolationTrait;
    use CreateValidatorTrait;

    /**
     * @param mixed $data
     * @param array<int, array<mixed>> $expectedViolations
     */
    #[DataProvider(methodName: 'dpTestArray')]
    #[DataProvider(methodName: 'dpTestBoolean')]
    #[DataProvider(methodName: 'dpTestEnum')]
    #[DataProvider(methodName: 'dpTestFloat')]
    #[DataProvider(methodName: 'dpTestInteger')]
    #[DataProvider(methodName: 'dpTestObject')]
    #[DataProvider(methodName: 'dpTestHashmap')]
    #[DataProvider(methodName: 'dpTestString')]
    #[DataProvider(methodName: 'dpTestMixed')]
    #[DataProvider(methodName: 'dpTestUnion')]
    #[DataProvider(methodName: 'dpTestCombined')]
    public function test(ValueSchemaInterface $schema, $data, array $expectedViolations): void
    {
        $result = $this->createValueValidator()->validate($schema, $data);
        $actualViolations = $result->getViolations();

        $this->assertViolations($expectedViolations, $actualViolations);
        $this->assertSame(empty($expectedViolations), $result->isValid(), 'isValid is different');
    }

    public static function dpTestArray(): array
    {
        return [
            'array:valid' => [new ArraySchemaData(new IntegerSchemaData()), [1, 2, 3], []],
            'array:valid-null' => [new ArraySchemaData(itemsSchema: new IntegerSchemaData(), nullable: true), null, []],
            'array:items-validation' => [
                new ArraySchemaData(new IntegerSchemaData()),
                ['a', null],
                [[1002, "Type '%s' expected.", ['integer'], '[0]'], [1001, 'Unexpected NULL value.', [], '[1]']],
            ],
            'array:null' => [
                new ArraySchemaData(new IntegerSchemaData()),
                null,
                [[1001, 'Unexpected NULL value.', [], '']]],
            'array:type' => [
                new ArraySchemaData(new IntegerSchemaData()),
                'string',
                [[1002, "Type '%s' expected.", ['array'], '']],
            ],
            'array:minItems' => [
                new ArraySchemaData(itemsSchema: new IntegerSchemaData(), minItems: 4),
                [1],
                [[1005, 'Items count has to be at least %d.', [4], '']],
            ],
            'array:maxItems' => [
                new ArraySchemaData(itemsSchema: new IntegerSchemaData(), maxItems: 1),
                [1, 2],
                [[1006, 'Items count has to be at most %d.', [1], '']],
            ],
            'array:unique' => [
                new ArraySchemaData(itemsSchema: new IntegerSchemaData(), uniqueItems: true),
                [1, 2, 2],
                [[1007, 'Items have to be unique.', [], '']],
            ],
        ];
    }

    public static function dpTestBoolean(): array
    {
        return [
            'bool:valid' => [new BooleanSchemaData(), true, []],
            'bool:valid-null' => [new BooleanSchemaData(true), null, []],
            'bool:null' => [new BooleanSchemaData(), null, [[1001, 'Unexpected NULL value.', [], '']]],
            'bool:type' => [new BooleanSchemaData(), 'string', [[1002, "Type '%s' expected.", ['boolean'], '']]],
        ];
    }

    public static function dpTestEnum(): array
    {
        return [
            'enum:valid' => [new EnumSchemaData(['a', 'b', 'c']), 'b', []],
            'enum:valid-null' => [new EnumSchemaData(['a', 'b', 'c'], true), null, []],
            'enum:null' => [new EnumSchemaData(['a']), null, [[1001, 'Unexpected NULL value.', [], '']]],
            'enum:type' => [new EnumSchemaData(['a']), false, [[1002, "Type '%s' expected.", ['string'], '']]],
            'enum:invalid-choice' => [
                new EnumSchemaData(['a', 'b']),
                'c',
                [[1008, 'Value has to be one of [%s].', ["'a', 'b'"], '']], ],
        ];
    }

    public static function dpTestFloat(): array
    {
        return [
            'float:valid' => [new FloatSchemaData(), 1.0, []],
            'float:valid-null' => [new FloatSchemaData(nullable: true), null, []],
            'float:valid-min' => [new FloatSchemaData(minimum: 3.0), 3.0, []],
            'float:valid-ex-min' => [new FloatSchemaData(minimum: 3.0), 5.0, []],
            'float:valid-max' => [new FloatSchemaData(maximum: 3.0), 3.0, []],
            'float:valid-ex-max' => [new FloatSchemaData(maximum: 3.0), 2.0, []],
            'float:valid-multipleOf' => [new FloatSchemaData(multipleOf: 2.3), 4.6, []],
            'float:int' => [new FloatSchemaData(), 1, []],
            'float:null' => [new FloatSchemaData(), null, [[1001, 'Unexpected NULL value.', [], '']]],
            'float:type' => [new FloatSchemaData(), '1.0', [[1002, "Type '%s' expected.", ['number'], '']]],
            'float:min' => [
                new FloatSchemaData(minimum: 1.0),
                0.5,
                [[1009, 'Value has to be bigger or equal then %s.', ['1'], '']], ],
            'float:ex-min' => [
                new FloatSchemaData(minimum: 1.0, exclusiveMinimum: true),
                0.5,
                [[1010, 'Value has to be bigger then %s.', ['1'], '']], ],
            'float:max' => [
                new FloatSchemaData(maximum: 1.0),
                1.5,
                [[1011, 'Value has to be less or equal then %s.', ['1'], '']], ],
            'float:ex-max' => [
                new FloatSchemaData(maximum: 1.0, exclusiveMaximum: true),
                1.5,
                [[1012, 'Value has to be less then %s.', ['1'], '']],
            ],
            'float:multipleOf' => [
                new FloatSchemaData(multipleOf: 2.3),
                5.5,
                [[1013, 'Value has to be divisible by %s.', ['2.3'], '']],
            ],
        ];
    }

    public static function dpTestInteger(): array
    {
        return [
            // int
            'int:valid' => [new IntegerSchemaData(), 1, []],
            'int:valid-null' => [new IntegerSchemaData(nullable: true), null, []],
            'int:valid-min' => [new IntegerSchemaData(minimum: 3), 3, []],
            'int:valid-ex-min' => [new IntegerSchemaData(minimum: 3, exclusiveMinimum: true), 5, []],
            'int:valid-max' => [new IntegerSchemaData(maximum: 3), 3, []],
            'int:valid-ex-max' => [new IntegerSchemaData(maximum: 3, exclusiveMaximum: true), 2, []],
            'int:valid-multipleOf' => [new IntegerSchemaData(multipleOf: 2), 4, []],
            'int:null' => [new IntegerSchemaData(), null, [[1001, 'Unexpected NULL value.', [], '']]],
            'int:type' => [new IntegerSchemaData(), 1.0, [[1002, "Type '%s' expected.", ['integer'], '']]],
            'int:min' => [
                new IntegerSchemaData(minimum: 3),
                2,
                [[1009, 'Value has to be bigger or equal then %s.', ['3'], '']], ],
            'int:ex-min' => [
                new IntegerSchemaData(minimum: 3, exclusiveMinimum: true),
                2,
                [[1010, 'Value has to be bigger then %s.', ['3'], '']], ],
            'int:max' => [
                new IntegerSchemaData(maximum: 1),
                5,
                [[1011, 'Value has to be less or equal then %s.', ['1'], '']], ],
            'int:ex-max' => [
                new IntegerSchemaData(maximum: 1, exclusiveMaximum: true),
                5,
                [[1012, 'Value has to be less then %s.', ['1'], '']], ],
            'int:multipleOf' => [
                new IntegerSchemaData(multipleOf: 2),
                5,
                [[1013, 'Value has to be divisible by %s.', ['2'], '']],
            ],
        ];
    }

    public static function dpTestObject(): array
    {
        return [
            'object:valid' => [
                new ObjectSchemaData([
                    'a' => new IntegerSchemaData(),
                    'b' => new IntegerSchemaData(),
                ], ['a']),
                (object) [
                    'a' => 1,
                    'b' => 2,
                ],
                [],
            ],
            'object:valid-null' => [new ObjectSchemaData([], [], true, null), null, []],
            'object:null' => [
                new ObjectSchemaData([
                    'a' => new IntegerSchemaData(),
                ]),
                null,
                [[1001, 'Unexpected NULL value.', [], '']],
            ],
            'object:type' => [
                new ObjectSchemaData([
                    'a' => new IntegerSchemaData(),
                ]),
                1.0,
                [[1002, "Type '%s' expected.", ['object'], '']],
            ],
            'object:required' => [
                new ObjectSchemaData([
                    'propertyName1' => new IntegerSchemaData(),
                ], ['propertyName1']),
                (object) [],
                [[1003, 'Required.', [], 'propertyName1']],
            ],
            'object:unexpected' => [
                new ObjectSchemaData([
                    'propertyName1' => new IntegerSchemaData(),
                ]),
                (object) [
                    'propertyNameUnknown' => 123,
                ],
                [[1004, 'Unexpected.', [], 'propertyNameUnknown']],
            ],
            'object:items-validation' => [
                new ObjectSchemaData([
                    'propertyName1' => new IntegerSchemaData(),
                ]),
                (object) [
                    'propertyName1' => false,
                ],
                [[1002, "Type '%s' expected.", ['integer'], 'propertyName1']],
            ],
        ];
    }

    public static function dpTestHashmap(): array
    {
        return [
            'hashmap:valid' => [
                new HashmapSchemaData(new IntegerSchemaData(), ['a']),
                (object) [
                    'a' => 1,
                    'b' => 2,
                ],
                [],
            ],
            'hashmap:valid-null' => [new HashmapSchemaData(new IntegerSchemaData(), [], true), null, []],
            'hashmap:null' => [
                new HashmapSchemaData(new IntegerSchemaData()),
                null,
                [[1001, 'Unexpected NULL value.', [], '']],
            ],
            'hashmap:type' => [
                new HashmapSchemaData(new IntegerSchemaData()),
                1.0,
                [[1002, "Type '%s' expected.", ['object'], '']],
            ],
            'hashmap:required' => [
                new HashmapSchemaData(new IntegerSchemaData(), ['propertyName1']),
                (object) [],
                [[1003, 'Required.', [], 'propertyName1']],
            ],
            'hashmap:items-validation' => [
                new HashmapSchemaData(new IntegerSchemaData(), [], false, null),
                (object) [
                    'propertyName1' => false,
                ],
                [[1002, "Type '%s' expected.", ['integer'], 'propertyName1']],
            ],
        ];
    }

    public static function dpTestString(): array
    {
        return [
            // string
            'string:valid' => [new StringSchemaData(), 'hello', []],
            'string:valid-null' => [new StringSchemaData(nullable: true), null, []],
            'string:valid-minLength-equal' => [new StringSchemaData(minLength: 1), 'a', []],
            'string:valid-minLength' => [new StringSchemaData(minLength: 1), 'aa', []],
            'string:valid-maxLength-equal' => [new StringSchemaData(maxLength: 3), 'aaa', []],
            'string:valid-maxLength' => [new StringSchemaData(maxLength: 3), 'aa', []],
            'string:valid-format' => [new StringSchemaData(format: 'date-time'), '2020-01-02T12:30:44.09+00:30', []],
            'string:valid-format-not-supported' => [new StringSchemaData(format: 'unknown-format'), 'string', []],
            'string:valid-pattern' => [new StringSchemaData(pattern: '[0-9]+'), '2020', []],
            'string:null' => [new StringSchemaData(), null, [[1001, 'Unexpected NULL value.', [], '']]],
            'string:type' => [new StringSchemaData(), 1.0, [[1002, "Type '%s' expected.", ['string'], '']]],
            'string:minLength' => [
                new StringSchemaData(minLength: 3),
                'nn',
                [[1014, 'Characters count has to be at least %d.', [3], '']], ],
            'string:maxLength' => [
                new StringSchemaData(maxLength: 1),
                'nn',
                [[1015, 'Characters count has to be at most %d.', [1], '']], ],
            'string:format' => [
                new StringSchemaData(format: 'date-time'),
                '2020-12-30',
                [[1016, "Value doesn't have format '%s'.", ['date-time'], '']],
            ],
            'string:format-regression-missing-plus' => [
                new StringSchemaData(format: 'date-time'),
                '2020-12-30T23:22:21 01:00',
                [[1016, "Value doesn't have format '%s'.", ['date-time'], '']],
            ],
            'string:pattern' => [
                new StringSchemaData(pattern: '-[0-9]{3}'),
                '2020-12-30',
                [[1017, "Value doesn't match pattern '%s'.", ['-[0-9]{3}'], '']],
            ],
        ];
    }

    public static function dpTestMixed(): array
    {
        return [
            'mixed:valid-string' => [new MixedSchemaData(), 'hello', []],
            'mixed:valid-int' => [new MixedSchemaData(), 1, []],
            'mixed:valid-float' => [new MixedSchemaData(), 1.0, []],
            'mixed:valid-bool' => [new MixedSchemaData(), true, []],
            'mixed:valid-null' => [new MixedSchemaData(true), null, []],
            'mixed:null' => [new MixedSchemaData(false), null, [[1001, 'Unexpected NULL value.', [], '']]],
        ];
    }

    public static function dpTestUnion(): array
    {
        return [
            'union:valid' => [new UnionSchemaData([new IntegerSchemaData(), new StringSchemaData()]), 'foo', []],
            'union:valid-null' => [
                new UnionSchemaData([new IntegerSchemaData(), new StringSchemaData()], null, true),
                null,
                [],
            ],
            'union:null' => [
                new UnionSchemaData([new IntegerSchemaData(), new StringSchemaData()]),
                null,
                [[1001, 'Unexpected NULL value.', [], '']],
            ],
            'union:no-match' => [
                new UnionSchemaData([new IntegerSchemaData(), new StringSchemaData()]),
                1.0,
                [[1018, "Value doesn't match any schema.", [], '']],
            ],
            'union:ambiguous' => [
                new UnionSchemaData([
                    new ObjectSchemaData([
                        'a' => new IntegerSchemaData(),
                        'b' => new StringSchemaData(),
                    ]),
                    new ObjectSchemaData([
                        'a' => new IntegerSchemaData(),
                        'c' => new BooleanSchemaData(),
                    ]),
                ]),
                (object) [
                    'a' => 3,
                ],
                [[1019, 'Value matches more then one schema.', [], '']],
            ],
            'union:missing-discriminator' => [
                new UnionSchemaData([
                    'aa' => new ObjectSchemaData([
                        'a' => new IntegerSchemaData(),
                    ]),
                    'bb' => new ObjectSchemaData([
                        'b' => new IntegerSchemaData(),
                    ]),
                ], 'type'),
                (object) [],
                [[1003, 'Required.', [], 'type']],
            ],
            'union:discriminator-invalid-value' => [
                new UnionSchemaData([
                    'aa' => new ObjectSchemaData([
                        'type' => new StringSchemaData(),
                        'a' => new IntegerSchemaData(),
                    ]),
                    'bb' => new ObjectSchemaData([
                        'type' => new StringSchemaData(),
                        'b' => new IntegerSchemaData(),
                    ]),
                ], 'type'),
                (object) [
                    'type' => 'wrong',
                ],
                [[1008, 'Value has to be one of [%s].', ["'aa', 'bb'"], 'type']],
            ],
            'union:invalid-data' => [
                new UnionSchemaData([
                    'aa' => new ObjectSchemaData([
                        'type' => new StringSchemaData(),
                        'a' => new IntegerSchemaData(3),
                    ]),
                    'bb' => new ObjectSchemaData([
                        'type' => new StringSchemaData(),
                        'b' => new IntegerSchemaData(),
                    ]),
                ], 'type'),
                (object) [
                    'type' => 'aa',
                    'a' => 1,
                ],
                [[1009, 'Value has to be bigger or equal then %s.', ['3'], 'a']],
            ],
        ];
    }

    public static function dpTestCombined(): array
    {
        return [
            // multiple
            'multiple' => [
                new ObjectSchemaData([
                    'int1' => new IntegerSchemaData(),
                    'int2' => new IntegerSchemaData(),
                    'str' => new StringSchemaData(100),
                    'obj' => new ObjectSchemaData([
                        'arr' => new ArraySchemaData(new IntegerSchemaData()),
                    ]),
                    'reqProp' => new StringSchemaData(),
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
                    [1003, 'Required.', [], 'reqProp'],
                    [1002, "Type '%s' expected.", ['integer'], 'int1'],
                    [1014, 'Characters count has to be at least %d.', [100], 'str'],
                    [1002, "Type '%s' expected.", ['integer'], 'obj.arr[1]'],
                ],
            ],
        ];
    }
}
