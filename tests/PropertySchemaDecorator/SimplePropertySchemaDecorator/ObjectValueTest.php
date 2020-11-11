<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\SimplePropertySchemaDecorator;

class ObjectValueTestClass
{
    public int $a;

    public int $b = 10;
}

class ObjectValueTest extends AbstractDecoratorTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(ObjectValueTestClass::class);
    }

    public function testRequiredProperties()
    {
        $schema = $this->schemaParser->getEntitySchema(ObjectValueTestClass::class);
        $this->assertEquals(['a'], $schema->getRequiredProperties());
    }
}
