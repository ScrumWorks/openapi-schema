<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\SimplePropertySchemaDecorator;

use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\SimplePropertySchemaDecorator;

class SimplePropertySchemaDecoratorTestClass
{
    public string $string;
}

class SimplePropertySchemaDecoratorTest extends AbstractDecoratorTest
{
    protected function createReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(SimplePropertySchemaDecoratorTestClass::class);
    }

    public function testIsEnum()
    {
        $decorator = new SimplePropertySchemaDecorator();
        $isEnum = $decorator->isEnum($this->getPropertyReflection('string'));
        $this->assertFalse($isEnum);
    }
}
