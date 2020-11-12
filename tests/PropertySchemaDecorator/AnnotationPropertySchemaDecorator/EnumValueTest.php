<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

class EnumValueTestClass
{
    /**
     * @OA\EnumValue(enum={"value1", "value2"})
     */
    public string $enum;

    /**
     * @OA\EnumValue(enum={})
     */
    public int $incompatibleTypes;

    /**
     * @OA\EnumValue(enum={})
     * @OA\StringValue()
     */
    public string $incompatibleAnnotations;
}

class EnumValueTest extends AbstractAnnotationTest
{
    public function testEnumAnnotation(): void
    {
        $schema = $this->getPropertySchema('enum');
        $expectedSchema = new EnumSchema(['value1', 'value2']);
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\EnumValue'");
        $this->getPropertySchema('incompatibleTypes');
    }

    public function testIncompatibleAnnotations(): void
    {
        // EnumValue has higher priority than StringValue
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\StringValue'");
        $this->getPropertySchema('incompatibleAnnotations');
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(EnumValueTestClass::class);
    }
}
