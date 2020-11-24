<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\AnnotationPropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\Validation\Result\BreadCrumbPathFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidityViolationFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValidatorFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValueSchemaValidator;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

abstract class AbstractAnnotationTest extends TestCase
{
    protected SchemaParser $schemaParser;

    protected ReflectionClass $reflection;

    protected function setUp(): void
    {
        $valueSchemaValidator = new ValueSchemaValidator(
            new ValidatorFactory(
                new BreadCrumbPathFactory(),
                new ValidationResultBuilderFactory(new ValidityViolationFactory())
            )
        );
        $this->schemaParser = new SchemaParser(
            new PropertyTypeReader(new VariableTypeUnifyService()),
            new AnnotationPropertySchemaDecorator(new AnnotationReader(), $valueSchemaValidator)
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
