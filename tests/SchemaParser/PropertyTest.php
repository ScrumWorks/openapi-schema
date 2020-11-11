<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser;

use ScrumWorks\OpenApiSchema\Annotation as OA;

class PropertyTestClass
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

class PropertyTest extends AbstractParserTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(PropertyTestClass::class);
    }

    public function testRequiredProperties()
    {
        $schema = $this->schemaParser->getEntitySchema(PropertyTestClass::class);
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
