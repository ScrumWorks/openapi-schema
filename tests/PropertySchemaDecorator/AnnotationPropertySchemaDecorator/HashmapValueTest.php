<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class HashmapValueTestClass
{
    /**
     * @var array<string, string>
     * @OA\HashmapValue(requiredProperties={"a", "b"})
     * @OA\Property(example="{""a"": ""test"", ""b"": ""test2"", ""c"": ""test3""}")
     */
    public array $hashmap;

    /**
     * @OA\HashmapValue(requiredProperties={"a", "b"})
     */
    public array $incompatibleTypes;
}

class HashmapValueTest extends AbstractAnnotationTest
{
    public function testHashmapAnnotation(): void
    {
        $schema = $this->getPropertySchema('hashmap');
        $expectedSchema = new HashmapSchema(
            new StringSchema(),
            ['a', 'b'],
            false,
            null,
            (object) [
                'a' => 'test',
                'b' => 'test2',
                'c' => 'test3',
            ]
        );
        $this->assertEquals($expectedSchema, $schema);
    }

    public function testIncompatibleTypes(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Unexpected annotation 'ScrumWorks\OpenApiSchema\Annotation\HashmapValue'");
        $this->getPropertySchema('incompatibleTypes');
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(HashmapValueTestClass::class);
    }
}
