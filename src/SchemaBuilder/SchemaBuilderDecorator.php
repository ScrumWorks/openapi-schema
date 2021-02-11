<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaBuilder;

use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassSchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertySchemaDecoratorInterface;
use ScrumWorks\OpenApiSchema\SchemaCollection\IClassSchemaCollection;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;

class SchemaBuilderDecorator
{
    /**
     * @var PropertySchemaDecoratorInterface[]
     */
    private array $propertyDecorators;

    /**
     * @var ClassSchemaDecoratorInterface[]
     */
    private array $classDecorators;

    /**
     * @param PropertySchemaDecoratorInterface[] $propertyDecorators
     * @param ClassSchemaDecoratorInterface[] $classDecorators
     */
    public function __construct(array $propertyDecorators, array $classDecorators)
    {
        $this->propertyDecorators = $propertyDecorators;
        $this->classDecorators = $classDecorators;
    }

    public function decorateClassSchemaBuilder(
        ObjectSchemaBuilder $builder,
        ReflectionClass $classReflection
    ): AbstractSchemaBuilder {
        foreach ($this->classDecorators as $propertyDecorator) {
            $builder = $propertyDecorator->decorateObjectSchemaBuilder($builder, $classReflection);
        }

        return $builder;
    }

    public function decoratePropertySchemaBuilder(
        AbstractSchemaBuilder $builder,
        ReflectionProperty $propertyReflection,
        IClassSchemaCollection $classSchemaCollection
    ): AbstractSchemaBuilder {
        foreach ($this->propertyDecorators as $propertyDecorator) {
            $builder = $propertyDecorator->decoratePropertySchemaBuilder(
                $builder,
                $propertyReflection,
                $classSchemaCollection
            );
        }

        return $builder;
    }
}
