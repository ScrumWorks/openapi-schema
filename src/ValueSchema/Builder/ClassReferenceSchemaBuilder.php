<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ClassReferenceBag;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\ReferenceSchema;

final class ClassReferenceSchemaBuilder extends AbstractSchemaBuilder
{
    private ?string $className = null;

    private ?ClassReferenceBag $referenceBag = null;

    public function withClassName(string $className)
    {
        $this->className = $className;
        return $this;
    }

    public function withReferenceBag(ClassReferenceBag $referenceBag)
    {
        $this->referenceBag = $referenceBag;
        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function getReferencedSchemaBuilder(): AbstractSchemaBuilder
    {
        if ($this->className === null) {
            throw new LogicException(
                "Can't obtain referenced class schema builder without filled 'className' proeprty"
            );
        }

        return $this->referenceBag->getClassBuilder($this->className);
    }

    public function build(): ReferenceSchema
    {
        if ($this->className === null) {
            throw new LogicException("'className' has to be set.");
        }

        if ($this->referenceBag === null) {
            throw new LogicException("'referenceBag' has to be set.");
        }

        $referenceName = $this->referenceBag->registerReference($this);
        return new ReferenceSchema($referenceName);
    }
}
