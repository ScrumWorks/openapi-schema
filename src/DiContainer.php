<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator\AnnotationClassSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertyDecorator\AnnotationPropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class DiContainer
{
    private array $services = [];

    /**
     * @phpstan-template T of object
     * @phpstan-param class-string<T> $class
     * @phpstan-param T $service
     */
    public function setService(string $class, object $service): void
    {
        $this->services[$class] = $service;
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
        $this->services[SchemaParserInterface::class] ??= new SchemaParser(
            new SchemaBuilderFactory(
                new PropertyTypeReader(new VariableTypeUnifyService()),
                new SchemaBuilderDecorator(
                    [new AnnotationPropertySchemaDecorator($this->getAnnotationReader())],
                    [new AnnotationClassSchemaDecorator($this->getAnnotationReader())]
                )
            )
        );

        return $this->services[SchemaParserInterface::class];
    }
}
