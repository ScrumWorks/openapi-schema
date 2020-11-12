<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\SimplePropertySchemaDecorator;

use ReflectionClass;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\SimplePropertySchemaDecorator;

class SimplePropertySchemaDecoratorTestClass
{
    public string $string;
}

class SimplePropertySchemaDecoratorTest extends AbstractDecoratorTest
{
    public function testIsEnum(): void
    {
        $decorator = new SimplePropertySchemaDecorator();
        $isEnum = $decorator->isEnum($this->getPropertyReflection('string'));
        $this->assertFalse($isEnum);
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(SimplePropertySchemaDecoratorTestClass::class);
    }
}
