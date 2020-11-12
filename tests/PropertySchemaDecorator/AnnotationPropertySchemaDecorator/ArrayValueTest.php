<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use LogicException;
use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class ArrayValueTestClass
{
    /**
     * @OA\ArrayValue(minItems=10, maxItems=20, uniqueItems=true)
     */
    public array $array;

    /**
     * @var string[]
     * @OA\ArrayValue(minItems=1, uniqueItems=true)
     */
    public array $typedArray;

    /**
     * @var int[][]
     * @OA\ArrayValue(minItems=0, maxItems=1, uniqueItems=false)
     */
    public array $nestedArray;

    /**
     * @OA\StringValue()
     */
    public array $incompatibleTypes;
}

class ArrayValueTest extends AbstractAnnotationTest
{
    public function testArrayAnnotation(): void
    {
        $schema = $this->getPropertySchema('array');
        $expectedSchema = new ArraySchema(new MixedSchema(true), 10, 20, true);
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testTypedArrayAnnotation(): void
    {
        // annotation works only outer array
        $schema = $this->getPropertySchema('typedArray');
        $expectedSchema = new ArraySchema(new StringSchema(), 1, null, true);
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testNestedArrayAnnotation(): void
    {
        $schema = $this->getPropertySchema('nestedArray');
        $expectedSchema = new ArraySchema(new ArraySchema(new IntegerSchema()), 0, 1, false);
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\StringValue'");
        $this->getPropertySchema('incompatibleTypes');
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(ArrayValueTestClass::class);
    }
}
