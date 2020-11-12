<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\SimplePropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

class ObjectValueTestClass
{
    public int $a;

    public int $b = 10;
}

class ObjectValueTest extends AbstractDecoratorTest
{
    public function testRequiredProperties(): void
    {
        /** @var ObjectSchema $schema */
        $schema = $this->schemaParser->getEntitySchema(ObjectValueTestClass::class);
        $this->assertEquals(['a'], $schema->getRequiredProperties());
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(ObjectValueTestClass::class);
    }
}
