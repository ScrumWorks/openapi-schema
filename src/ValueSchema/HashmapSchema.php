<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class HashmapSchema extends AbstractValueSchema
{
    private ?ValueSchemaInterface $itemsSchema;

    public function __construct(?ValueSchemaInterface $itemsSchema, bool $nullable, ?string $description)
    {
        parent::__construct($nullable, $description);

        $this->itemsSchema = $itemsSchema;
    }

    public function getItemsSchema(): ?ValueSchemaInterface
    {
        return $this->itemsSchema;
    }
}
