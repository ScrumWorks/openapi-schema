<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\Annotation;

use ScrumWorks\OpenApiSchema\Annotation as OA;

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
}

class PropertyTest extends AbstractAnnotationTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(PropertyTestClass::class);
    }

    public function testPropertyDescription()
    {
        $schema = $this->getPropertySchema('description');
        $this->assertEquals('my description...', $schema->getDescription());
    }

    public function testPropertyDescriptionIsNullable()
    {
        $schema = $this->getPropertySchema('descriptionNullable');
        $this->assertEquals(null, $schema->getDescription());
    }

    public function testPropertyCanBeUserWithOtherAnnotations()
    {
        $schema = $this->getPropertySchema('mixedAnnotations');
        $this->assertEquals('other description', $schema->getDescription());
    }
}
