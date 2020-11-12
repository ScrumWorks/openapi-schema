<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

class IntegerValueTestClass
{
    /**
     * @OA\IntegerValue(minimum=10, maximum=100, multipleOf=10, exclusiveMinimum=true, exclusiveMaximum=false)
     */
    public int $integer;

    /**
     * @OA\StringValue()
     */
    public int $incompatibleTypes;
}

class IntegerValueTest extends AbstractAnnotationTest
{
    public function testIntegerAnnotation(): void
    {
        $schema = $this->getPropertySchema('integer');
        $expectedSchema = new IntegerSchema(10, 100, true, false, 10);
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
        return new ReflectionClass(IntegerValueTestClass::class);
    }
}
