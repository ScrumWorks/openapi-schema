<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator\AnnotationClassSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertyDecorator\AnnotationPropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class TestSubEntity
{
    /**
     * @OA\IntegerValue(minimum=25)
     * @OA\Property(description="sub...")
     */
    public int $subInteger;
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
     * @OA\ArrayValue(minItems=3, maxItems=7, uniqueItems=true)
     * @OA\Property(required=true)
     */
    public array $array;

    /**
     * @var array<string,int[]>
     *
     * @OA\HashmapValue(requiredProperties={"reqKey"})
     */
    public array $hashmap = [];

    public TestSubEntity $class;
}

class SchemaParserTest extends TestCase
{
    protected SchemaParser $schemaParser;

    protected function setUp(): void
    {
        $annotationReader = new AnnotationReader();
        $this->schemaParser = new SchemaParser(
            new SchemaBuilderFactory(
                new PropertyTypeReader(new VariableTypeUnifyService()),
                new SchemaBuilderDecorator(
                    [new AnnotationPropertySchemaDecorator($annotationReader)],
                    [new AnnotationClassSchemaDecorator($annotationReader)]
                )
            )
        );
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
        $this->assertInstanceOf(IntegerSchema::class, $arraySchema->getItemsSchema());

        /** @var HashmapSchema $hashmapSchema */
        $hashmapSchema = $entitySchema->getPropertySchema('hashmap');
        $this->assertInstanceOf(HashmapSchema::class, $hashmapSchema);
        $this->assertFalse($hashmapSchema->isNullable());
        $this->assertSame(['reqKey'], $hashmapSchema->getRequiredProperties());
        /** @var ArraySchema $arrayItemSchema */
        $arrayItemSchema = $hashmapSchema->getItemsSchema();
        $this->assertInstanceOf(ArraySchema::class, $arrayItemSchema);
        $this->assertInstanceOf(IntegerSchema::class, $arrayItemSchema->getItemsSchema());

        /** @var ObjectSchema $objectSchema */
        $objectSchema = $entitySchema->getPropertySchema('class');
        $this->assertInstanceOf(ObjectSchema::class, $objectSchema);
        $this->assertFalse($objectSchema->isNullable());
        $this->assertNull($objectSchema->getDescription());
        /** @var IntegerSchema $subIntSchema */
        $subIntSchema = $objectSchema->getPropertySchema('subInteger');
        $this->assertInstanceOf(IntegerSchema::class, $subIntSchema);
        $this->assertFalse($subIntSchema->isNullable());
        $this->assertSame('sub...', $subIntSchema->getDescription());
        $this->assertSame(25, $subIntSchema->getMinimum());
    }
}
