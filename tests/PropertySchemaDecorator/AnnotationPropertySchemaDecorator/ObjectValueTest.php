<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Tests\SchemaParser\PropertyTestClass;

class ObjectValueTestClass
{
    public int $a;

    public int $b = 10;

    /**
     * @OA\Property(required=false)
     */
    public int $c;

    /**
     * @OA\Property(required=true)
     */
    public int $d = 10;
}

class ObjectValueTest extends AbstractAnnotationTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(ObjectValueTestClass::class);
    }

    public function testRequiredProperties()
    {
        $schema = $this->schemaParser->getEntitySchema(ObjectValueTestClass::class);
        // $a is required, because doesn't have default value
        // $b isn't required, because it's have default value
        // $c may be required, but it's overwritten by annotation
        // $d may not be required, but it's overwritten by annotation
        $this->assertEquals(
            ['a', 'd'],
            $schema->getRequiredProperties()
        );
    }
}
