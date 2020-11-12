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

class ValueValidatorExamplesTest extends TestCase
{
    use AssertViolationTrait;
    use CreateValidatorTrait;

    /**
     * @dataProvider dpTest
     *
     * @param array<int, array<mixed>> $expectedViolations
     */
    public function test(ValueSchemaInterface $schema, array $expectedViolations): void
    {
        $actualViolations = $this->createValueValidator()->getPossibleViolationExamples($schema);

        $this->assertViolations($expectedViolations, $actualViolations);
    }

    public function dpTest(): array
    {
        return [
            'array' => [
                new ArraySchema(new IntegerSchema(), 1, 4, true),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'array' expected.", ''],
                    [1005, 'Minimal count 1 expected.', ''],
                    [1006, 'Maximal count 4 expected.', ''],
                    [1007, 'It has to be unique.', ''],
                    [1001, 'Unexpected NULL value.', '[0]'],
                    [1002, "Type 'integer' expected.", '[0]'],
                ],
            ],
            'bool' => [
                new BooleanSchema(),
                [[1001, 'Unexpected NULL value.', ''], [1002, "Type 'boolean' expected.", '']],
            ],
            'enum' => [
                new EnumSchema(['a', 'b']),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'string' expected.", ''],
                    [1008, "It has to be one of 'a|b'.", ''],
                ],
            ],
            'float' => [
                new FloatSchema(0, 100, true, false, 2),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'number' expected.", ''],
                    [1010, 'It has to be bigger then 0.', ''],
                    [1011, 'It has to be less or equal then 100.', ''],
                    [1013, 'It has to be divisible by 2.', ''],
                ],
            ],
            'int' => [
                new IntegerSchema(1, 100, false, true, 20),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'integer' expected.", ''],
                    [1009, 'It has to be bigger or equal then 1.', ''],
                    [1012, 'It has to be less then 100.', ''],
                    [1013, 'It has to be divisible by 20.', ''],
                ],
            ],
            'object' => [
                new ObjectSchema([
                    'a' => new BooleanSchema(true),
                    'b' => new IntegerSchema(),
                ]),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'object' expected.", ''],
                    [1003, 'It is required.', 'property'],
                    [1004, 'It is unexpected.', 'unknownProperty'],
                    [1002, "Type 'boolean' expected.", 'a'],
                    [1001, 'Unexpected NULL value.', 'b'],
                    [1002, "Type 'integer' expected.", 'b'],
                ],
            ],
            'hashmap' => [
                new HashmapSchema(new IntegerSchema()),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'object' expected.", ''],
                    [1003, 'It is required.', 'key'],
                    [1001, 'Unexpected NULL value.', 'key'],
                    [1002, "Type 'integer' expected.", 'key'],
                ],
            ],
            'string' => [
                new StringSchema(1, 10, 'date', '[0-9]+'),
                [
                    [1001, 'Unexpected NULL value.', ''],
                    [1002, "Type 'string' expected.", ''],
                    [1014, 'Minimal length has to be 1.', ''],
                    [1015, 'Maximal length has to be 10.', ''],
                    [1017, "It has to match pattern '[0-9]+'.", ''],
                    [1016, "It has to match format 'date'.", ''],
                ],
            ],
            'mixed' => [new MixedSchema(), [[1001, 'Unexpected NULL value.', '']]],
        ];
    }
}
