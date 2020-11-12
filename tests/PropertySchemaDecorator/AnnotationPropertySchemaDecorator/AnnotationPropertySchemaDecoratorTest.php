<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

class AnnotationPropertySchemaDecoratorTestClass
{
    /**
     * @OA\EnumValue(enum={"a", "b"})
     */
    public string $isEnum;

    public string $notEnum;
}

class AnnotationPropertySchemaDecoratorTest extends AbstractAnnotationTest
{
    public function testIsEnum(): void
    {
        $decorator = new AnnotationPropertySchemaDecorator(new AnnotationReader());

        $isEnum = $decorator->isEnum($this->getPropertyReflection('isEnum'));
        $this->assertTrue($isEnum);

        $notEnum = $decorator->isEnum($this->getPropertyReflection('notEnum'));
        $this->assertFalse($notEnum);
    }

    protected function createReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(AnnotationPropertySchemaDecoratorTestClass::class);
    }
}
