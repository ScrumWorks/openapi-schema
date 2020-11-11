<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class HashmapSchema extends AbstractValueSchema
{
    private ValueSchemaInterface $itemsSchema;

    /**
     * @var string[]
     */
    private array $requiredProperties;

    public function __construct(
        ValueSchemaInterface $itemsSchema,
        array $requiredProperties,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        $this->itemsSchema = $itemsSchema;
        $this->requiredProperties = $requiredProperties;
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
}
