<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class HashmapValueTestClass
{
    /**
     * @var array<string, string>
     * @OA\HashmapValue(requiredProperties={"a", "b"})
     */
    public array $hashmap;

    /**
     * @OA\HashmapValue(requiredProperties={"a", "b"})
     */
    public array $incompatibleTypes;
}

class HashmapValueTest extends AbstractAnnotationTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(HashmapValueTestClass::class);
    }

    public function testHashmapAnnotation()
    {
        $schema = $this->getPropertySchema('hashmap');
        $expectedSchema = new HashmapSchema(
            new StringSchema(),
            ['a', 'b']
        );
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\HashmapValue'");
        $this->getPropertySchema('incompatibleTypes');
    }
}
