<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use DateTimeInterface;
use ScrumWorks\OpenApiSchema\Annotation as OA;

final class TestEntity
{
    /**
     * @OA\IntegerValue(minimum=1, maximum=5, exclusiveMaximum=true)
     * @OA\Property(required=false, description="Important integer")
     */
    public int $integer;

    /**
     * @OA\FloatValue(minimum=10.3, maximum=50.5, exclusiveMinimum=true, exclusiveMaximum=false)
     * @OA\Property(required=true, description="Important float")
     */
    public float $float = 13.3;

    /**
     * @OA\EnumValue(enum={"a","b"})
     */
    public ?string $enum = null;

    /**
     * @OA\StringValue(minLength=10, maxLength=100, format="date", pattern="[0-9]+")
     */
    public ?string $string;

    /**
     * @var int[]
     * @OA\ArrayValue(minItems=3, maxItems=7, uniqueItems=true, itemsSchema=@OA\IntegerValue(minimum=3))
     * @OA\Property(required=true)
     */
    public array $array;

    /**
     * @var array<string,int[]>
     * @OA\HashmapValue(
     *     requiredProperties={"reqKey"},
     *     itemsSchema=@OA\ArrayValue(maxItems=10, itemsSchema=@OA\IntegerValue(multipleOf=2))
     * )
     */
    public array $hashmap = [];

    public TestSubEntity $class;

    /**
     * @var int|string|null
     *
     * @OA\Union(types={ @OA\IntegerValue(minimum=2), @OA\StringValue(minLength=10) })
     */
    public $scalarUnion;

    /**
     * @var AEntity|BEntity
     *
     * @OA\Union(discriminator="type", mapping={ "a": "AEnt", "b": "BEnt" })
     */
    public $objectUnion;

    /**
     * @OA\Property(description="Moment")
     */
    public ?DateTimeInterface $dateTime = null;
}
