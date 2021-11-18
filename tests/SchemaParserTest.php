<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;

/**
 * @OA\ComponentSchema(schemaName="subEntity")
 */
class TestSubEntity
{
    /**
     * @OA\IntegerValue(minimum=25)
     * @OA\Property(description="sub...")
     */
    public int $subInteger;
}

/**
 * @OA\ComponentSchema(schemaName="AEnt")
 */
class AEntity
{
    public string $type;
}

/**
 * @OA\ComponentSchema(schemaName="BEnt")
 */
class BEntity
{
    public string $type;
}

class TestEntity
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

final class SchemaParserTest extends TestCase
{
    use DiTrait;

    protected SchemaParserInterface $schemaParser;

    protected function setUp(): void
    {
        $this->schemaParser = $this->getServiceFromContainerByType(SchemaParserInterface::class);
    }

    public function testNonExistingEntity(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Class or interface 'abc-not-existing' does not exist");
        $this->schemaParser->getEntitySchema('abc-not-existing');
    }

    public function testEntity(): void
    {
        /** @var ObjectSchema $entitySchema */
        $entitySchema = $this->schemaParser->getEntitySchema(TestEntity::class);
        $this->assertInstanceOf(ObjectSchema::class, $entitySchema);
        $this->assertFalse($entitySchema->isNullable());
        $this->assertNull($entitySchema->getDescription());
        $this->assertNull($entitySchema->getSchemaName());
        $this->assertSame(['float', 'string', 'array', 'class'], $entitySchema->getRequiredProperties());

        /** @var IntegerSchema $integerSchema */
        $integerSchema = $entitySchema->getPropertySchema('integer');
        $this->assertInstanceOf(IntegerSchema::class, $integerSchema);
        $this->assertFalse($integerSchema->isNullable());
        $this->assertSame('Important integer', $integerSchema->getDescription());
        $this->assertSame(1, $integerSchema->getMinimum());
        $this->assertSame(5, $integerSchema->getMaximum());
        $this->assertNull($integerSchema->getExclusiveMinimum());
        $this->assertTrue($integerSchema->getExclusiveMaximum());
        $this->assertNull($integerSchema->getMultipleOf());

        /** @var FloatSchema $floatSchema */
        $floatSchema = $entitySchema->getPropertySchema('float');
        $this->assertInstanceOf(FloatSchema::class, $floatSchema);
        $this->assertFalse($floatSchema->isNullable());
        $this->assertSame('Important float', $floatSchema->getDescription());
        $this->assertSame(10.3, $floatSchema->getMinimum());
        $this->assertSame(50.5, $floatSchema->getMaximum());
        $this->assertTrue($floatSchema->getExclusiveMinimum());
        $this->assertFalse($floatSchema->getExclusiveMaximum());
        $this->assertNull($integerSchema->getMultipleOf());

        /** @var EnumSchema $enumSchema */
        $enumSchema = $entitySchema->getPropertySchema('enum');
        $this->assertInstanceOf(EnumSchema::class, $enumSchema);
        $this->assertTrue($enumSchema->isNullable());
        $this->assertNull($enumSchema->getDescription());
        $this->assertSame(['a', 'b'], $enumSchema->getEnum());

        /** @var StringSchema $stringSchema */
        $stringSchema = $entitySchema->getPropertySchema('string');
        $this->assertInstanceOf(StringSchema::class, $stringSchema);
        $this->assertTrue($stringSchema->isNullable());
        $this->assertNull($stringSchema->getDescription());
        $this->assertSame(10, $stringSchema->getMinLength());
        $this->assertSame(100, $stringSchema->getMaxLength());
        $this->assertSame('date', $stringSchema->getFormat());
        $this->assertSame('[0-9]+', $stringSchema->getPattern());

        /** @var ArraySchema $arraySchema */
        $arraySchema = $entitySchema->getPropertySchema('array');
        $this->assertInstanceOf(ArraySchema::class, $arraySchema);
        $this->assertFalse($arraySchema->isNullable());
        $this->assertNull($arraySchema->getDescription());
        $this->assertSame(3, $arraySchema->getMinItems());
        $this->assertSame(7, $arraySchema->getMaxItems());
        $this->assertTrue($arraySchema->getUniqueItems());
        /** @var IntegerSchema $arrayItemSchema */
        $arrayItemSchema = $arraySchema->getItemsSchema();
        $this->assertInstanceOf(IntegerSchema::class, $arrayItemSchema);
        $this->assertSame(3, $arrayItemSchema->getMinimum());

        /** @var HashmapSchema $hashmapSchema */
        $hashmapSchema = $entitySchema->getPropertySchema('hashmap');
        $this->assertInstanceOf(HashmapSchema::class, $hashmapSchema);
        $this->assertFalse($hashmapSchema->isNullable());
        $this->assertSame(['reqKey'], $hashmapSchema->getRequiredProperties());
        /** @var ArraySchema $hashmapItemSchema */
        $hashmapItemSchema = $hashmapSchema->getItemsSchema();
        $this->assertInstanceOf(ArraySchema::class, $hashmapItemSchema);
        $this->assertSame(10, $hashmapItemSchema->getMaxItems());
        /** @var IntegerSchema $arrayItemSchema */
        $arrayItemSchema = $hashmapItemSchema->getItemsSchema();
        $this->assertInstanceOf(IntegerSchema::class, $arrayItemSchema);
        $this->assertSame(2, $arrayItemSchema->getMultipleOf());

        /** @var ObjectSchema $objectSchema */
        $objectSchema = $entitySchema->getPropertySchema('class');
        $this->assertInstanceOf(ObjectSchema::class, $objectSchema);
        $this->assertFalse($objectSchema->isNullable());
        $this->assertNull($objectSchema->getDescription());
        $this->assertSame('subEntity', $objectSchema->getSchemaName());
        /** @var IntegerSchema $subIntSchema */
        $subIntSchema = $objectSchema->getPropertySchema('subInteger');
        $this->assertInstanceOf(IntegerSchema::class, $subIntSchema);
        $this->assertFalse($subIntSchema->isNullable());
        $this->assertSame('sub...', $subIntSchema->getDescription());
        $this->assertSame(25, $subIntSchema->getMinimum());

        /** @var UnionSchema $scalarUnionSchema */
        $scalarUnionSchema = $entitySchema->getPropertySchema('scalarUnion');
        $this->assertInstanceOf(UnionSchema::class, $scalarUnionSchema);
        $this->assertTrue($scalarUnionSchema->isNullable());
        $this->assertEquals([new IntegerSchema(2), new StringSchema(10)], $scalarUnionSchema->getPossibleSchemas());

        /** @var UnionSchema $objectUnionSchema */
        $objectUnionSchema = $entitySchema->getPropertySchema('objectUnion');
        $this->assertInstanceOf(UnionSchema::class, $objectUnionSchema);
        $this->assertFalse($objectUnionSchema->isNullable());
        $this->assertSame('type', $objectUnionSchema->getDiscriminatorPropertyName());

        $this->assertEquals([
            'a' => new ObjectSchema([
                'type' => new StringSchema(),
            ], ['type'], false, null, 'AEnt'),
            'b' => new ObjectSchema([
                'type' => new StringSchema(),
            ], ['type'], false, null, 'BEnt'),
        ], $objectUnionSchema->getPossibleSchemas());

        /** @var StringSchema $dateTimeSchema */
        $dateTimeSchema = $entitySchema->getPropertySchema('dateTime');
        $this->assertInstanceOf(StringSchema::class, $dateTimeSchema);
        $this->assertTrue($dateTimeSchema->isNullable());
        $this->assertSame('Moment', $dateTimeSchema->getDescription());
        $this->assertSame('date-time', $dateTimeSchema->getFormat());
    }
}
