<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ClassReferenceSchemaBuilder;

class ClassReferenceBag
{
    /**
     * @var array<string, AbstractSchemaBuilder>
     *
     * this is common class builder where we don't look for `nullable` and
     * `description` properties, they will be overwritten by ClassReferenceSchemaBuilder,
     * but they shared as basic definition between multiple ClassReferenceSchemaBuilder's
     */
    private array $commonClassBuilders = [];

    /**
     * @var array<string, ClassReferenceSchemaBuilder>
     */
    private array $referenceBuilders = [];

    public function addClassBuilder(string $className, AbstractSchemaBuilder $builder): void
    {
        $this->commonClassBuilders[$className] = $builder;
    }

    public function hasClassBuilder(string $className): bool
    {
        return isset($this->commonClassBuilders[$className]);
    }

    public function getClassBuilder(string $className): AbstractSchemaBuilder
    {
        // TODO: check
        if (! isset($this->commonClassBuilders[$className])) {
            throw new LogicException("Unknown class name '{$className}");
        }
        return $this->commonClassBuilders[$className];
    }

    public function getClassBuilderReference(string $className): ClassReferenceSchemaBuilder
    {
        if (! isset($this->commonClassBuilders[$className])) {
            // throw
        }

        if ($this->commonClassBuilders[$className]->getSchemaName() === null) {
            // throw
        }

        return (new ClassReferenceSchemaBuilder())
            ->withClassName($className)
            ->withReferenceBag($this);
    }

    public function registerReference(ClassReferenceSchemaBuilder $builder): string
    {
        $referenceName = $this->getReferenceName($builder);
        $this->referenceBuilders[$referenceName] = $builder;
        return $referenceName;
    }

    public function build(): ReferencedSchemaBag
    {
        foreach ($this->referenceBuilders as $builder) {
            $this->commonClassBuilders[$builder->getClassName()]->build();
        }

        $referencedSchemas = [];
        foreach ($this->referenceBuilders as $schemaName => $builder) {
            $innerBuilder = clone $this->commonClassBuilders[$builder->getClassName()];
            $innerBuilder
                ->withNullable($builder->isNullable())
                ->withDescription($builder->getDescription());
            $referencedSchemas[$schemaName] = $innerBuilder->build();
        }

        return new ReferencedSchemaBag($referencedSchemas);
    }

    private function getReferenceName(ClassReferenceSchemaBuilder $builder): string
    {
        $className = $builder->getClassName();
        if ($className === null) {
            // throw
        }

        if (! isset($this->commonClassBuilders[$className])) {
            // throw
        }

        $schemaName = $this->commonClassBuilders[$className]->getSchemaName() ?: \str_replace('\\', '/', $className);
        $nullable = $builder->isNullable() ? 'Nullable' : '';
        $description = $builder->getDescription() ? \sha1($builder->getDescription()) : '';

        return $schemaName . $nullable . $description;
    }
}
