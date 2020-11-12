<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\SimplePropertySchemaDecorator;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\SimplePropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

abstract class AbstractDecoratorTest extends TestCase
{
    protected SchemaParser $schemaParser;

    protected ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->schemaParser = new SchemaParser(
            new PropertyTypeReader(new VariableTypeUnifyService(), ),
            new SimplePropertySchemaDecorator()
        );
        $this->reflection = $this->createReflectionClass();
    }

    abstract protected function createReflectionClass(): ReflectionClass;

    protected function getPropertyReflection(string $propertyName): ReflectionProperty
    {
        try {
            return $this->reflection->getProperty($propertyName);
        } catch (ReflectionException $e) {
            $this->fail(\sprintf(
                "Expected property '%s' not exists on class %s",
                $propertyName,
                $this->reflection->getName()
            ));
        }
    }

    protected function getPropertySchema(string $propertyName): ValueSchemaInterface
    {
        return $this->schemaParser->getPropertySchema($this->getPropertyReflection($propertyName));
    }
}
