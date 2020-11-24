<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class StringValueTestClass
{
    /**
     * @OA\StringValue(minLength=2, maxLength=10, format="email", pattern="[a-z]+")
     */
    public string $string;

    /**
     * @OA\StringValue(minLength=4, maxLength=6, pattern="[a-z0-9]+")
     * @OA\Property(example="""test1""")
     */
    public string $stringExample;

    /**
     * @OA\IntegerValue()
     */
    public string $incompatibleTypes;
}

class StringValueTest extends AbstractAnnotationTest
{
    public function testStringAnnotation(): void
    {
        $schema = $this->getPropertySchema('string');
        $expectedSchema = new StringSchema(2, 10, 'email', '[a-z]+');
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testStringExampleAnnotation(): void
    {
        $schema = $this->getPropertySchema('stringExample');
        $expectedSchema = new StringSchema(4, 6, null, '[a-z0-9]+', false, null, 'test1');
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\IntegerValue'");
        $this->getPropertySchema('incompatibleTypes');
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(StringValueTestClass::class);
    }
}
