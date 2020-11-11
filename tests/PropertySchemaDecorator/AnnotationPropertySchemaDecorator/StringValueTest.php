<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class StringValueTestClass
{
    /**
     * @OA\StringValue(minLength=2, maxLength=10, format="email", pattern="[a-z]+")
     */
    public string $string;

    /**
     * @OA\IntegerValue()
     */
    public string $incompatibleTypes;
}

class StringValueTest extends AbstractAnnotationTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(StringValueTestClass::class);
    }

    public function testStringAnnotation()
    {
        $schema = $this->getPropertySchema('string');
        $expectedSchema = new StringSchema(2, 10, 'email', '[a-z]+');
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\IntegerValue'");
        $this->getPropertySchema('incompatibleTypes');
    }
}
