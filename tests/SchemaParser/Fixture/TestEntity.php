<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use DateTimeInterface;
use ScrumWorks\OpenApiSchema\Attribute as OA;

class TestEntity
{
    #[OA\IntegerValue(minimum: 1, maximum: 5, exclusiveMaximum: true)]
    #[OA\Property(description: 'Important integer', required: false)]
    public int $integer;

    #[OA\FloatValue(minimum: 10.3, maximum: 50.5, exclusiveMinimum: true, exclusiveMaximum: false)]
    #[OA\Property(description: 'Important float', required: true)]
    public float $float = 13.3;

    #[OA\EnumValue(enum: ['a', 'b'])]
    public ?string $enum = null;

    #[OA\StringValue(minLength: 10, maxLength: 100, format: 'date', pattern: '[0-9]+')]
    public ?string $string;

    /**
     * @var int[]
     */
    #[OA\ArrayValue(minItems: 3, maxItems: 7, uniqueItems: true, itemsSchema: new OA\IntegerValue(minimum: 3))]
    #[OA\Property(required: true)]
    public array $array;

    /**
     * @var array<string,int[]>
     */
    #[OA\HashmapValue(
        requiredProperties: ['reqKey'],
        itemsSchema: new OA\ArrayValue(maxItems: 10, itemsSchema: new OA\IntegerValue(multipleOf: 2)),
    )]
    public array $hashmap = [];

    public TestSubEntity $class;

    /**
     * @var int|string|null
     */
    #[OA\Union(types: [new OA\IntegerValue(minimum: 2), new OA\StringValue(minLength: 10)])]
    public $scalarUnion;

    #[OA\Union(discriminator: 'type', mapping: [
        'a' => 'AEnt',
        'b' => 'BEnt',
    ])]
    public AEntity|BEntity $objectUnion;

    #[OA\Property(description: 'Moment', deprecated: true)]
    public ?DateTimeInterface $dateTime = null;
}
