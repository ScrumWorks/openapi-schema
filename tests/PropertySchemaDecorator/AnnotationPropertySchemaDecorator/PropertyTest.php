<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class PropertyTestClass
{
    /**
     * @OA\Property(description="my description...")
     */
    public string $description;

    /**
     * @OA\Property()
     */
    public string $descriptionIsNullable;

    /**
     * @OA\Property(description="other description")
     * @OA\StringValue()
     */
    public string $mixedAnnotations;

    /**
     * @OA\StringValue()
     */
    public $mixed;

    /**
     * @OA\Property(nullable=true)
     */
    public string $string;

    /**
     * @OA\Property(nullable=false)
     */
    public ?int $nullableInt;
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
        $schema = $this->getPropertySchema('descriptionIsNullable');
        $this->assertEquals(null, $schema->getDescription());
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

    public function testRewriteNullabilityOfProperty(): void
    {
        // we flip nullability of properties by annotation
        $schema = $this->getPropertySchema('string');
        $this->assertEquals(new StringSchema(null, null, null, null, true, null), $schema);

        $schema = $this->getPropertySchema('nullableInt');
        $this->assertEquals(new IntegerSchema(null, null, null, null, null, false, null), $schema);
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(PropertyTestClass::class);
    }
}
