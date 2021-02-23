<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator\AnnotationClassSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertyDecorator\AnnotationPropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertySchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\BreadCrumbPathFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidityViolationFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValidatorFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValueSchemaValidator;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\PropertyTypeReaderInterface;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class DiContainer
{
    /**
     * @var array<string, mixed>
     */
    private array $services = [];

    /**
     * @var PropertySchemaDecoratorInterface[]|null
     */
    private ?array $propertySchemaDecorators = null;

    /**
     * @var ClassSchemaDecoratorInterface[]|null
     */
    private ?array $classSchemaDecorators = null;

    /**
     * @phpstan-template T of object
     * @phpstan-param class-string<T> $class
     * @phpstan-param T $service
     */
    public function setService(string $class, object $service): void
    {
        $this->services[$class] = $service;
    }

    public function registerPropertySchemaDecorator(PropertySchemaDecoratorInterface $decorator): void
    {
        $this->getPropertySchemaDecorators();
        $this->propertySchemaDecorators[] = $decorator;
    }

    public function registerClassSchemaDecorator(ClassSchemaDecoratorInterface $decorator): void
    {
        $this->getClassSchemaDecorators();
        $this->classSchemaDecorators[] = $decorator;
    }

    public function getAnnotationReader(): Reader
    {
        $this->services[Reader::class] ??= new AnnotationReader();
        return $this->services[Reader::class];
    }

    public function getOpenApiTranslator(): OpenApiTranslatorInterface
    {
        $this->services[OpenApiTranslatorInterface::class] ??= new OpenApiTranslator();
        return $this->services[OpenApiTranslatorInterface::class];
    }

    public function getSchemaParser(): SchemaParserInterface
    {
        $this->services[SchemaParserInterface::class] ??= new SchemaParser($this->getSchemaBuilderFactory());
        return $this->services[SchemaParserInterface::class];
    }

    public function getPropertyReader(): PropertyTypeReaderInterface
    {
        $this->services[PropertyTypeReaderInterface::class] ??= new PropertyTypeReader(new VariableTypeUnifyService());
        return $this->services[PropertyTypeReaderInterface::class];
    }

    public function getSchemaBuilderFactory(): SchemaBuilderFactory
    {
        $this->services[SchemaBuilderFactory::class] ??= new SchemaBuilderFactory(
            $this->getPropertyReader(),
            new SchemaBuilderDecorator($this->getPropertySchemaDecorators(), $this->getClassSchemaDecorators())
        );
        return $this->services[SchemaBuilderFactory::class];
    }

    public function getValidityViolationFactory(): ValidityViolationFactoryInterface
    {
        $this->services[ValidityViolationFactoryInterface::class] ??= new ValidityViolationFactory();
        return $this->services[ValidityViolationFactoryInterface::class];
    }

    public function getBreadCrumbPathFactory(): BreadCrumbPathFactoryInterface
    {
        $this->services[BreadCrumbPathFactoryInterface::class] ??= new BreadCrumbPathFactory();
        return $this->services[BreadCrumbPathFactoryInterface::class];
    }

    public function getValueSchemaValidator(): ValueSchemaValidatorInterface
    {
        $this->services[ValueSchemaValidatorInterface::class] ??= new ValueSchemaValidator(
            new ValidatorFactory(
                $this->getBreadCrumbPathFactory(),
                new ValidationResultBuilderFactory($this->getValidityViolationFactory())
            )
        );
        return $this->services[ValueSchemaValidatorInterface::class];
    }

    /**
     * @return PropertySchemaDecoratorInterface[]
     */
    private function getPropertySchemaDecorators(): array
    {
        $this->propertySchemaDecorators ??= [new AnnotationPropertySchemaDecorator($this->getAnnotationReader())];
        return $this->propertySchemaDecorators;
    }

    /**
     * @return ClassSchemaDecoratorInterface[]
     */
    private function getClassSchemaDecorators(): array
    {
        $this->classSchemaDecorators ??= [new AnnotationClassSchemaDecorator($this->getAnnotationReader())];
        return $this->classSchemaDecorators;
    }
}
