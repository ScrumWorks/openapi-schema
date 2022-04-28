<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class ComponentSchema implements ValueInterface
{
    public function __construct(
        private readonly ?string $schemaName = null,
    ) {
    }

    public function getSchemaName(): ?string
    {
        return $this->schemaName;
    }
}
