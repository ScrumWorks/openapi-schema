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
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['array'], ''],
                    [1005, 'Items count has to be at least %d.', [1], ''],
                    [1006, 'Items count has to be at most %d.', [4], ''],
                    [1007, 'Items have to be unique.', [], ''],
                    [1001, 'Unexpected NULL value.', [], '[0]'],
                    [1002, "Type '%s' expected.", ['integer'], '[0]'],
                ],
            ],
            'bool' => [
                new BooleanSchema(),
                [[1001, 'Unexpected NULL value.', [], ''], [1002, "Type '%s' expected.", ['boolean'], '']],
            ],
            'enum' => [
                new EnumSchema(['a', 'b']),
                [
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['string'], ''],
                    [1008, 'Value has to be one of [%s].', ["'a', 'b'"], ''],
                ],
            ],
            'float' => [
                new FloatSchema(0, 100, true, false, 2),
                [
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['number'], ''],
                    [1010, 'Value has to be bigger then %s.', ['0'], ''],
                    [1011, 'Value has to be less or equal then %s.', ['100'], ''],
                    [1013, 'Value has to be divisible by %s.', ['2'], ''],
                ],
            ],
            'int' => [
                new IntegerSchema(1, 100, false, true, 20),
                [
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['integer'], ''],
                    [1009, 'Value has to be bigger or equal then %s.', ['1'], ''],
                    [1012, 'Value has to be less then %s.', ['100'], ''],
                    [1013, 'Value has to be divisible by %s.', ['20'], ''],
                ],
            ],
            'object' => [
                new ObjectSchema([
                    'a' => new BooleanSchema(true),
                    'b' => new IntegerSchema(),
                ], ['a']),
                [
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['object'], ''],
                    [1004, 'Unexpected.', [], '-unknown-property-'],
                    [1003, 'Required.', [], 'a'],
                    [1002, "Type '%s' expected.", ['boolean'], 'a'],
                    [1001, 'Unexpected NULL value.', [], 'b'],
                    [1002, "Type '%s' expected.", ['integer'], 'b'],
                ],
            ],
            'hashmap' => [
                new HashmapSchema(new IntegerSchema(), ['req']),
                [
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['object'], ''],
                    [1003, 'Required.', [], 'req'],
                    [1001, 'Unexpected NULL value.', [], '-key-'],
                    [1002, "Type '%s' expected.", ['integer'], '-key-'],
                ],
            ],
            'string' => [
                new StringSchema(1, 10, 'date', '[0-9]+'),
                [
                    [1001, 'Unexpected NULL value.', [], ''],
                    [1002, "Type '%s' expected.", ['string'], ''],
                    [1014, 'Characters count has to be at least %d.', [1], ''],
                    [1015, 'Characters count has to be at most %d.', [10], ''],
                    [1017, "Value doesn't match pattern '%s'.", ['[0-9]+'], ''],
                    [1016, "Value doesn't have format '%s'.", ['date'], ''],
                ],
            ],
            'mixed' => [new MixedSchema(), [[1001, 'Unexpected NULL value.', [], '']]],
        ];
    }
}
