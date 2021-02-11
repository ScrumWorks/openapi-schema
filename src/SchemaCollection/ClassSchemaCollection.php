<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaCollection;

use InvalidArgumentException;
use LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ClassReferenceBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class ClassSchemaCollection implements IClassSchemaCollection
{
    private string $componentsSchemasPath;

    /**
     * @var array<string, AbstractSchemaBuilder|true>
     */
    private array $schemaBuilders = [];

    public function __construct(string $componentsSchemasPath)
    {
        $this->componentsSchemasPath = \rtrim($componentsSchemasPath, '/');
    }

    public function registerReference(string $className): void
    {
        $this->schemaBuilders[$className] = true;
    }

    public function hasReference(string $className): bool
    {
        return isset($this->schemaBuilders[$className]);
    }

    public function getReferencePath(string $className): string
    {
        if (! isset($this->schemaBuilders[$className]) || ! $this->schemaBuilders[$className] instanceof AbstractSchemaBuilder) {
            throw new LogicException("Unregistered builder for class '${className}'");
        }

        if (! $this->schemaBuilders[$className]->getSchemaName()) {
            throw new LogicException(\sprintf(
                "Builder '%s' doesn't have registered schema name!",
                \get_class($this->schemaBuilders[$className])
            ));
        }

        return $this->componentsSchemasPath . '/' . $this->schemaBuilders[$className]->getSchemaName();
    }

    public function createReferenceBuilder(string $className): AbstractSchemaBuilder
    {
        if (! isset($this->schemaBuilders[$className])) {
            throw new LogicException("Unregistered class '${className}'");
        }

        $classReferenceBuilder = new ClassReferenceBuilder();
        $classReferenceBuilder = $classReferenceBuilder->withClassName($className);
        return $classReferenceBuilder->withClassSchemaCollection($this);
    }

    public function getSchemaForReference(string $referencePath): ValueSchemaInterface
    {
        if (! str_starts_with($referencePath, $this->componentsSchemasPath)) {
            throw new LogicException(\sprintf("Unprocessable reference path '%s'", $referencePath));
        }

        static $schemas = [];
        $referenceName = \substr($referencePath, \strlen($this->componentsSchemasPath) + 1);
        if (isset($schemas[$referencePath])) {
            return $schemas[$referencePath];
        }

        foreach ($this->schemaBuilders as $componentSchema) {
            if ($componentSchema->getSchemaName() === $referenceName) {
                $schemas[$referencePath] = $componentSchema->build();
                return $schemas[$referencePath];
            }
        }

        throw new InvalidArgumentException(\sprintf("Unknown reference path '%s'", $referencePath));
    }

    public function addBuilder(string $className, AbstractSchemaBuilder $schemaBuilder): void
    {
        $this->schemaBuilders[$className] = $schemaBuilder;
    }

    public function hasBuilder(string $className): bool
    {
        return isset($this->schemaBuilders[$className]);
    }

    public function getBuilder(string $className): AbstractSchemaBuilder
    {
        if (! isset($this->schemaBuilders[$className]) || ! $this->schemaBuilders[$className] instanceof AbstractSchemaBuilder) {
            throw new LogicException("Unregistered builder for class '${className}'");
        }

        return $this->schemaBuilders[$className];
    }
}
