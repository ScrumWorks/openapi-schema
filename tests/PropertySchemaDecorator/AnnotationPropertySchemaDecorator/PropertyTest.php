<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Exception\ExampleValidationException;
use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;

class PropertyTestClass
{
    /**
     * @OA\Property(description="my description...")
     */
    public string $description;

    /**
     * @OA\Property(example="""test""")
     */
    public string $example;

    /**
     * @OA\Property(example="123")
     */
    public string $badTypesExample;

    /**
     * @OA\Property(example="{""test""")
     */
    public string $malformedExample;

    /**
     * @OA\Property()
     */
    public string $descriptionNullable;

    /**
     * @OA\Property(description="other description")
     * @OA\StringValue()
     */
    public string $mixedAnnotations;

    /**
     * @OA\StringValue()
     */
    public $mixed;
}

class PropertyTest extends AbstractAnnotationTest
{
    public function testPropertyDescription(): void
    {
        $schema = $this->getPropertySchema('description');
        $this->assertEquals('my description...', $schema->getDescription());
    }

    public function testPropertyDescriptionIsNullable(): void
    {
        $schema = $this->getPropertySchema('descriptionNullable');
        $this->assertEquals(null, $schema->getDescription());
    }

    public function testPropertyExample(): void
    {
        $schema = $this->getPropertySchema('example');
        $this->assertEquals('test', $schema->getExample());
    }

    public function testPropertyBadExample(): void
    {
        $this->expectException(ExampleValidationException::class);
        $this->expectExceptionMessage('Example schema validation error');
        $this->getPropertySchema('badTypesExample');
        /** @var ExampleValidationException $exception */
        $exception = $this->getExpectedException();
        $this->assertInstanceOf(ValidationResultInterface::class, $exception->getValidationResult());
        $this->assertCount(1, $exception->getValidationResult()->getViolations());
    }

    public function testPropertyMalformedExample(): void
    {
        $this->expectException(ExampleValidationException::class);
        $this->expectExceptionMessage('Malformed JSON syntax for example {"test"');
        $this->getPropertySchema('malformedExample');
    }

    public function testPropertyCanBeUserWithOtherAnnotations(): void
    {
        $schema = $this->getPropertySchema('mixedAnnotations');
        $this->assertEquals('other description', $schema->getDescription());
    }

    public function testAnnotationNotOverwriteType(): void
    {
        $schema = $this->getPropertySchema('mixed');
        $this->assertEquals(new MixedSchema(true), $schema);
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(PropertyTestClass::class);
    }
}
