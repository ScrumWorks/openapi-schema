<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

class FloatValueTestClass
{
    /**
     * @OA\FloatValue(minimum=10.2, maximum=100.0, multipleOf=10.1, exclusiveMinimum=true, exclusiveMaximum=false)
     */
    public float $float;

    /**
     * @OA\StringValue()
     */
    public float $incompatibleTypes;
}

class FloatValueTest extends AbstractAnnotationTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(FloatValueTestClass::class);
    }

    public function testFloatAnnotation()
    {
        $schema = $this->getPropertySchema('float');
        $expectedSchema = new FloatSchema(10.2, 100.0, true, false, 10.1);
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\StringValue'");
        $this->getPropertySchema('incompatibleTypes');
    }
}
