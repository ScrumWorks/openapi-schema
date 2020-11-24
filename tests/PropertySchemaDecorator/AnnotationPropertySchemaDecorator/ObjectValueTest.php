<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

class ObjectValueTestClass
{
    public int $a;

    public int $b = 10;

    /**
     * @OA\Property(required=false)
     */
    public int $c;

    /**
     * @OA\IntegerValue(minimum=2)
     * @OA\Property(required=true)
     */
    public int $d = 10;
}

class ObjectValuePropertyTestClass
{
    /**
     * @OA\Property(example="{""a"": 1, ""c"": 2, ""d"": 12}")
     */
    public ObjectValueTestClass $class;
}

class ObjectValueTest extends AbstractAnnotationTest
{
    public function testRequiredProperties(): void
    {
        /** @var ObjectSchema $schema */
        $schema = $this->schemaParser->getEntitySchema(ObjectValueTestClass::class);
        // $a is required, because doesn't have default value
        // $b isn't required, because it's have default value
        // $c may be required, but it's overwritten by annotation
        // $d may not be required, but it's overwritten by annotation
        //    also testing that required reading from @Property work
        //    with another annotation
        $this->assertEquals(['a', 'd'], $schema->getRequiredProperties());
    }

    public function testPropertyExample(): void
    {
        /** @var ObjectSchema $schema */
        $schema = $this->schemaParser->getEntitySchema(ObjectValuePropertyTestClass::class);
        $this->assertEquals(
            (object) [
                'a' => 1,
                'c' => 2,
                'd' => 12,
            ],
            $schema->getPropertySchema('class')->getExample()
        );
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(ObjectValueTestClass::class);
    }
}
