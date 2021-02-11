<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use LogicException;
use ScrumWorks\OpenApiSchema\SchemaCollection\IClassSchemaCollection;
use ScrumWorks\OpenApiSchema\ValueSchema\Reference;

final class ClassReferenceBuilder extends AbstractSchemaBuilder
{
    protected ?string $className = null;

    protected ?IClassSchemaCollection $classSchemaCollection = null;

    /**
     * @return static
     */
    public function withClassName(string $className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return static
     */
    public function withClassSchemaCollection(IClassSchemaCollection $classSchemaCollection)
    {
        $this->classSchemaCollection = $classSchemaCollection;
        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function getClassSchemaCollection(): ?IClassSchemaCollection
    {
        return $this->classSchemaCollection;
    }

    public function getSchemaName(): ?string
    {
        return $this->classSchemaCollection->getBuilder($this->className)->getSchemaName();
    }

    public function build(): Reference
    {
        if (! $this->className) {
            throw new LogicException('$this->className has to be set');
        }
        if (! $this->classSchemaCollection) {
            throw new LogicException('$this->classSchemaCollection has to be set');
        }

        return new Reference($this->classSchemaCollection->getReferencePath($this->className));
    }
}
