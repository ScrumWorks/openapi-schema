<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class HashmapSchemaData extends AbstractValueSchema implements HashmapSchema
{
    /**
     * @param string[] $requiredProperties
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly ValueSchemaInterface $itemsSchema,
        private readonly array $requiredProperties = [],
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false,
        array $metaData = [],
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated, $metaData);
    }

    public function getItemsSchema(): ValueSchemaInterface
    {
        return $this->itemsSchema;
    }

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }

    protected function validate(): void
    {
    }
}
