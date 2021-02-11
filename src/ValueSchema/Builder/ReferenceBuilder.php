<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Reference;

final class ReferenceBuilder extends AbstractSchemaBuilder
{
    protected ?string $referencePath = null;

    /**
     * @return static
     */
    public function withReferencePath(string $referencePath)
    {
        $this->referencePath = $referencePath;
        return $this;
    }

    public function getReferencePath(): ?string
    {
        return $this->referencePath;
    }

    public function build(): Reference
    {
        if (! $this->referencePath) {
            throw new LogicException('$this->referencePath has to be set');
        }

        return new Reference($this->referencePath);
    }
}
