<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface UnionSchema extends ValueSchemaInterface
{
    /**
     * @return ValueSchemaInterface[]
     */
    public function getPossibleSchemas(): array;

    public function getDiscriminatorPropertyName(): ?string;
}
