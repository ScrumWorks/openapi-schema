<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\Tests\DiTrait;
use ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture\InvalidEntity;
use ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture\TestEntity;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\IntegerSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ObjectSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;

class SchemaParserTest extends TestCase
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

    public function testValidationFailed(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            InvalidEntity::class . ': property `subEntity`: items: property `emptyEnum`: Enum has to be set.'
        );
        $this->schemaParser->getEntitySchema(InvalidEntity::class);
    }

    public function testEntity(): void
    {
        /** @var ObjectSchema $entitySchema */
        $entitySchema = $this->schemaParser->getEntitySchema(TestEntity::class);
        $this->assertInstanceOf(ObjectSchema::class, $entitySchema);
        $this->assertFalse($entitySchema->isNullable());
        $this->assertNull($entitySchema->getDescription());
        $this->assertNull($entitySchema->getSchemaName());
        $this->assertSame(['float', 'string', 'array', 'class', 'objectUnion'], $entitySchema->getRequiredProperties());

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
        $this->assertEquals(
            [new IntegerSchemaData(2), new StringSchemaData(10)],
            $scalarUnionSchema->getPossibleSchemas(),
        );

        /** @var UnionSchema $objectUnionSchema */
        $objectUnionSchema = $entitySchema->getPropertySchema('objectUnion');
        $this->assertInstanceOf(UnionSchema::class, $objectUnionSchema);
        $this->assertFalse($objectUnionSchema->isNullable());
        $this->assertSame('type', $objectUnionSchema->getDiscriminatorPropertyName());
        $this->assertEquals([
            'a' => new ObjectSchemaData([
                'type' => new StringSchemaData(),
            ], ['type'], false, null, 'AEnt'),
            'b' => new ObjectSchemaData([
                'type' => new StringSchemaData(),
            ], ['type'], false, null, 'BEnt'),
        ], $objectUnionSchema->getPossibleSchemas());

        /** @var StringSchema $dateTimeSchema */
        $dateTimeSchema = $entitySchema->getPropertySchema('dateTime');
        $this->assertInstanceOf(StringSchema::class, $dateTimeSchema);
        $this->assertTrue($dateTimeSchema->isNullable());
        $this->assertTrue($dateTimeSchema->isDeprecated());
        $this->assertSame('Moment', $dateTimeSchema->getDescription());
        $this->assertSame('date-time', $dateTimeSchema->getFormat());
    }
}
