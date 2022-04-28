<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class HashmapSchema extends AbstractValueSchema
{
    /**
     * @param string[] $requiredProperties
     */
    public function __construct(
        private readonly ValueSchemaInterface $itemsSchema,
        private readonly array $requiredProperties = [],
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated);
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
