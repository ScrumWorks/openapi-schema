<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\SchemaCollection;

use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface IClassSchemaCollection
{
    public function registerReference(string $className): void;

    public function hasReference(string $className): bool;

    public function getReferencePath(string $className): string;

    public function createReferenceBuilder(string $classname): AbstractSchemaBuilder;

    public function getSchemaForReference(string $referencePath): ValueSchemaInterface;

    public function addBuilder(string $className, AbstractSchemaBuilder $schemaBuilder): void;

    public function hasBuilder(string $className): bool;

    public function getBuilder(string $className): AbstractSchemaBuilder;
}
