<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;

class PropertyTestClass
{
    /**
     * @OA\Property(description="my description...")
     */
    public string $description;

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
